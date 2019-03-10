<?php
/**
 * Renders publish major publishing elements
 *
 * @package littlebot_netlifly/views
 */

if ( $has_prod_hook || $has_stage_hook ) : ?>
	<h4 style="margin-bottom: 0;"><?php esc_html_e( 'Save as', 'lbn-netlifly' ); ?>:</h4>
	<div><label><input data-env="stage" type="radio" name="lbn_published" value="lbn_published_stage" <?php if ( $published_stage ) : ?>checked<?php endif; ?>>Draft</label></div>
	<div><label><input data-env="production" type="radio" name="lbn_published" value="lbn_published_production" <?php if ( $published_production ) : ?>checked<?php endif; ?>>Publication</label></div>
	<br/>
	<div><label>Content will be available in a few minutes (~2min)</label></div>
<?php else : ?>
	<div class="no-hooks">
		<?php
			$url = get_site_url() . '/wp-admin/options-general.php?page=lb-netlifly';
			echo sprintf( wp_kses( __( 'Opps, you need to <a href="%s">set a production or stage build hook</a> for this plugin to work.', 'lb-netlifly' ),
				array( 'a' => array( 'href' => array() ) ) ), esc_url( $url )
			);
		?>
	</div>
<?php endif; ?>