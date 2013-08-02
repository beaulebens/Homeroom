<?php

// Hardcoded for now
function homeroom_options() {
	return array(
		/**
		 * We can optionally collapse posts of the same post format which
		 * appear one after another (within the same day) to avoid flooding
		 * the page. When set to true, subsequent posts will be wrapped in
		 * a div and automaticaly collapsed; a link will be provided to expand
		 * them back out again.
		 */
		'collapse_consecutive_posts' => true,

		/**
		 * Despite other settings, you can choose to disable all maps
		 * from appearing on your homepage. If you're rendering tweets
		 * and maps and everything all at once, it can slow things down
		 * horribly. This allows you to hide all maps from the homepage.
		 */
		'no_maps_on_homepage' => false,

		/**
		 * A Google Maps API key is not actually required to make Maps work.
		 * If you have one, you can enter it here, or just set it to "true".
		 *  They're free, and you can get one here:
		 *  https://developers.google.com/maps/documentation/javascript/tutorial?hl=nl#api_key
		 */
		'google_maps_api_key' => 'AIzaSyAeH_bDOuGPtNuji2Wt--4YSQ3MymIbpWA',

		/**
		 * Should we hide/collate Foursquare checkins on the homepage?
		 * If you use Foursquare a lot then you probably want this to be true,
		 * since otherwise your check-ins will "overrun" your main pages
		 */
		'hide_checkins_on_home' => true,

		/**
		 * How many HOURS should checkins remain hidden for before showing up (anywhere)?
		 * Set to 0 to show them as soon as they are available.
		 */
		'hide_checkins_for_hours' => 2,

		/**
		 * Should we hide Twitter @replies on the homepage? Normally this is a good idea
		 * because they tend to clutter things up without much context.
		 */
		'hide_twitter_replies' => true,

		/**
		 * Set this to true to enable fancy Twitter embeds, using their official code
		 */
		'enable_twitter_embeds' => true,

		/**
		 * Enable a simple posting box right on the homepage.
		 * Only shows for logged in users who are allowed to create new posts.
		 */
		'enable_frontend_postbox' => true, // Not implemented yet

		/**
		 * Which style layout should we use for search results?
		 * Current options: 'masonry', 'timeline' (default)
		 */
		'search_results_view' => 'masonry',

		/**
		 * Which types of posts should be shown on the homepage? This is an array
		 * of service names that should match Keyring Social Importer names. You can (probably should)
		 * also include 'posts' to show manually posted content.
		 */
		'posts_filter' => array( 'posts', 'instapaper' ),
	);
}
