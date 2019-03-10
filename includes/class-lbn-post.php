<?php
/**
 * LittleBot Netlifly
 *
 * A class for all plugin metaboxs.
 *
 * @version   0.9.0
 * @category  Class
 * @package   LittleBotNetlifly
 * @author    Justin W Hall
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Hooks saving and updating posts.
 */
class LBN_Post {

	/**
	 * Parent plugin class.
	 *
	 * @var object
	 */
	protected $plugin = null;

	/**
	 * Kick it off.
	 *
	 * @param object $plugin the parent class.
	 */
	function __construct( $plugin ) {
		$this->plugin = $plugin;
		$this->hooks();
	}

	/**
	 * Attach hooks.
	 *
	 * @return void
	 */
	public function hooks() {
		add_action( 'save_post', array( $this, 'save_post' ), 10, 3 );
		add_action( 'delete_post', array( $this, 'save_post' ), 10, 3 );
		add_action( 'wp_insert_post_data', array( $this, 'insert_post' ), 10, 3 );
		add_filter( 'manage_posts_columns', array( $this, 'add_publish_status'), 10, 3);
		add_action( 'manage_posts_custom_column', array( $this, 'build_column'), 15, 3 );
		add_filter( 'manage_edit-post_sortable_columns', array( $this, 'sortable_status'), 15, 3 );
	}

	/**
	 * Add publish column
	 *
	 * @param array $columns Post list columns.
	 *
	 * @return array
	 */
	public function add_publish_status( $columns ) {
		$columns['state'] = 'Status';
		return $columns;
	}

	/**
	 * Add columns to invoice and estimates
	 *
	 * @param  array $columns post screen columns.
	 * @param  int   $post_id   the post id.
	 * @return void
	 */
	public function build_column( $column, $post_id ) {
		switch ( $column ) {
			case 'state' :
				$stage_status = (bool) get_post_meta( $post_id, 'lbn_published_stage', true );
				$prod_status = (bool) get_post_meta( $post_id, 'lbn_published_production', true );

				if ( $prod_status ) {
					echo 'Published';
				}

				if ( $stage_status ) {
					echo 'Draft';
				}

				if ( ! $stage_status && ! $prod_status ) {
					echo '—';
				}
				break;
		}
	}

	public function sortable_status( $columns ) {
		$columns['state'] = 'Status';
		return $columns;
	}

	/**
	 * Updates "deploy" status on post update
	 *
	 * @param object $data the $_POST request.
	 * @param object $post the post being updated.
	 *
	 * @return object
	 */
	public function insert_post( $data, $post ) {
		if (
			isset( $post['post_status'] ) && 'auto-draft' === $post['post_status'] ||
			defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ||
			defined( 'DOING_AJAX' ) && DOING_AJAX
			) {
			return $data;
		}

		// If it's a deploy, make sure it's set to publish.
		if ( isset( $post['deploy'] ) ) {
			$data['post_status'] = 'publish';
		}

		return $data;
	}

	/**
	 * Save post callback
	 *
	 * @param int     $post_id The post ID.
	 * @param object  $post    The post object.
	 * @param boolean $update  Is this an update.
	 * @return void
	 */
	public function save_post( $post_id, $post, $update ) {
		// Bail if it's a auto-draft, we're doing auto save or ajax.
		if (
			isset( $post->post_status ) && 'auto-draft' === $post->post_status ||
			defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ||
			defined( 'DOING_AJAX' ) && DOING_AJAX
			) {
			return;
		}
		if (isset($_POST['lbn_published'])) {
			$val = $_POST["lbn_published"];
			if($val == "lbn_published_stage") {
				update_post_meta( $post->ID, 'lbn_published_stage', true );
				update_post_meta( $post->ID, 'lbn_published_production', false );
			}
			else if ($val == "lbn_published_production") {
				update_post_meta( $post->ID, 'lbn_published_production', true );
				update_post_meta( $post->ID, 'lbn_published_stage', false );
			}

			$netlifly_stage = new LBN_Netlifly( 'stage' );
			$netlifly_prod = new LBN_Netlifly( 'production' );

			// Deploy to both env, will only create page depending on the lbn_published_x variable
			$netlifly_stage->call_build_hook();
			$netlifly_prod->call_build_hook();
		}
	}
}
