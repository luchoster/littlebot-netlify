# LittleBot Netlify

Connect your WordPress website to [Netlify](https://www.netlify.com/) by triggering stage and or production build hooks on post save and or update.

## Installation

- Download or clone repository
- Move `littlebot-netlify` to your plugins directory or zip and upload
- Activate plugin
- Create a stage and production site at Netlify
- Create a build hook at each
- Add build hook to the Settings > LittleBot Netlify

## How to unpublish / handle draft

### Remove post from an environment:

- Select the environment to remove the post from
- Publish to :
  - Production (If you want to remove it from Prod)
  - Stage (If you want to remove it from Stage)
- Save Draft or Draft
- Post will be removed from checked envs as it will trigger corresponding hooks

### Update a post on an env while on draft on another env

- Simply switch to Publish while selecting the correct env on which you want this post to be displayed.
  - Ex : If the post is on Draft on the Prod env, click on Stage on the “Publish To:” then click on Publish. This will now only trigger the Stage hook, and thus will display it on Stage and not on Prod

## Gatsby + WordPress + Netlify Starter

[Gatsby + WordPress + Netlify Starter](https://github.com/justinwhall/gatsby-wordpress-netlify-starter) is a plug and play starter to get up and running with continous deployment from your WordPress site to Netifly with Gatsby.
