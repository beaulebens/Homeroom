<?php
/**
 * This is Homeroom.
 *
 * @package Homeroom
 * @since Homeroom 1.0
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 *
 * @since Homeroom 1.0
 */
if ( ! isset( $content_width ) )
	$content_width = 607; /* pixels */

/**
 * Core Homeroom class which gets things done.
 */
class Homeroom {
	var $map_markers    = array();
	var $multimap_start = false;
	var $multimap_end   = false;
	var $last_map       = false;
	var $just_did_map   = false;

	function __construct() {
		// @todo remove this once there is a UI for all options
		require get_template_directory() . '/options.php';

		// Basic theme, widget, etc set up
		$this->setup();
		add_action( 'widgets_init',        array( $this, 'widgets_init'     ) );
		add_filter( 'user_contactmethods', array( $this, 'contact_methods'  ) );
		add_action( 'body_class',          array( $this, 'body_class'       ) );
		add_filter( 'wp_title',            array( $this, 'wp_title'         ), 10, 2 );
		add_filter( 'the_content',         array( $this, 'oembed_helper'    ), 1, 1 );
		add_filter( 'the_content',         array( $this, 'dynamic_headings' ) );
		add_filter( 'the_content',         array( $this, 'child_pages'      ) );
		add_filter( 'the_content',         array( $this, 'post_flair_shortlink' ) );

		// Load some custom user-facing JS/CSS
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		// Post manipulation/look-ahead/behind.
		add_action( 'before_post', function() { global $homeroom; $homeroom->just_did_map = false; } );
		add_action( 'after_post', array( $this, 'collate_checkins' ) );

		// Query Filters
		add_action( 'pre_get_posts', array( $this, 'hide_twitter_replies' ) );
		add_action( 'pre_get_posts', array( $this, 'hide_foursquare_checkins' ) );

		if ( is_admin() )
			require get_template_directory() . '/inc/admin.php';
	}

	function setup() {
		/**
		 * Custom Header
		 */
		require get_template_directory() . '/inc/custom-header.php';

		/**
		 * Custom template tags for this theme.
		 */
		require get_template_directory() . '/inc/template-tags.php';

		/**
		 * Customizer
		 */
		require get_template_directory() . '/inc/customizer.php';

		/**
		 * Make theme available for translation
		 * Translations can be filed in the /languages/ directory
		 * If you're building a theme based on Homeroom, use a find and replace
		 * to change 'homeroom' to the name of your theme in all the template files
		 */
		load_theme_textdomain( 'homeroom', get_template_directory() . '/languages' );

		/**
		 * Add default posts and comments RSS feed links to head
		 */
		add_theme_support( 'automatic-feed-links' );

		/**
		 * Enable support for Post Thumbnails
		 */
		add_theme_support( 'post-thumbnails' );

		/**
		 * Enable custom background so that users can upload images/pick colors
		 */
		add_theme_support( 'custom-background', array(
			'default-color' => 'fdfdfa', /* Thanks, Readability */
		) );

		/**
		 * This theme uses wp_nav_menu() in one location.
		 */
		register_nav_menus( array(
			'main' => __( 'Main Site Menu', 'homeroom' ),
		) );

		/**
		 * Enable support for all the post formats we're using
		 */
		add_theme_support( 'post-formats', array( 'aside', 'status', 'link', 'image', 'video', 'quote' ) );

		/**
		 * Infinite Scroll, via Jetpack
		 * @see http://jetpack.me/support/infinite-scroll/
		 */
		add_theme_support( 'infinite-scroll', array(
			'container'      => 'timeline',
			'footer'         => 'colophon',
			'type'           => 'scroll',
			'footer'         => false,
			'wrapper'        => false,
			'posts_per_page' => get_option( 'posts_per_page' ),
		) );
	}

	function widgets_init() {
		// Sidebar, show on most screens
		register_sidebar( array(
			'name'          => __( 'Sidebar', 'homeroom' ),
			'description'   => __( 'This is the main sidebar, shown on most pages.', 'homeroom' ),
			'id'            => 'sidebar-1',
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget'  => "</aside>",
			'before_title'  => '<h1 class="widget-title">',
			'after_title'   => '</h1>',
		) );

		// Wide Footer, shown on single pages if defined, overrides the 3 below
		register_sidebar( array(
			'name'          => __( 'Footer - Full-width', 'homeroom' ),
			'description'   => __( 'A full-width footer which spans the whole page (left to right) at the bottom of single page views. If you want 3 columns, leave this empty and use the next 3 widget areas instead.', 'homeroom' ),
			'id'            => 'footer-wide',
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget'  => "</aside>",
			'before_title'  => '<h1 class="widget-title">',
			'after_title'   => '</h1>',
		) );

		// If you want 3 columns of footer widgets, then use these
		register_sidebar( array(
			'name'          => __( 'Footer - Left', 'homeroom' ),
			'description'   => __( 'The left column of your footer (disabled if the full-width footer has widgets in it).', 'homeroom' ),
			'id'            => 'footer-left',
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget'  => "</aside>",
			'before_title'  => '<h1 class="widget-title">',
			'after_title'   => '</h1>',
		) );
		register_sidebar( array(
			'name'          => __( 'Footer - Center', 'homeroom' ),
			'description'   => __( 'The center column of your footer (disabled if the full-width footer has widgets in it).', 'homeroom' ),
			'id'            => 'footer-center',
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget'  => "</aside>",
			'before_title'  => '<h1 class="widget-title">',
			'after_title'   => '</h1>',
		) );
		register_sidebar( array(
			'name'          => __( 'Footer - Right', 'homeroom' ),
			'description'   => __( 'The right column of your footer (disabled if the full-width footer has widgets in it).', 'homeroom' ),
			'id'            => 'footer-right',
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget'  => "</aside>",
			'before_title'  => '<h1 class="widget-title">',
			'after_title'   => '</h1>',
		) );
	}

	function enqueue_scripts() {
		global $post;

		wp_enqueue_style( 'style', get_stylesheet_uri() );
		wp_enqueue_style( 'homeroom-css', dirname( get_stylesheet_uri() ) . '/homeroom.css' );

		wp_enqueue_script( 'small-menu', get_template_directory_uri() . '/js/small-menu.js', array( 'jquery' ), '20120206', true );

		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}

		// Global JS file for all additional functionality
		wp_enqueue_script( 'homeroom-js', get_template_directory_uri() . '/js/homeroom.js', array( 'jquery' ), '20130101' );

		// Add tag suggest script for the homepage/editor
		if ( Homeroom::get_option( 'enable_frontend_postbox' ) && is_home() && is_user_logged_in() && current_user_can( 'publish_posts' ) && !get_query_var( 'paged' ) )
			wp_enqueue_script( 'suggest', false, array(), false, true );


		// Embedded Tweets, for non-mobile only, being careful to use the same name as the Jetpack Twitter widget
		// See https://dev.twitter.com/docs/embedded-tweets
		if (
			! function_exists( 'jetpack_is_mobile' )
		||
			! jetpack_is_mobile()
		&&
			Homeroom::get_option( 'enable_twitter_embeds' )
		) {
			wp_enqueue_script(
				'twitter-widgets',
				'//platform.twitter.com/widgets.js',
				false, // no deps
				false, // version
				true // footer
			);
		}

		// Google Maps API
		if (
			!is_home()
		||
			( is_home() && !Homeroom::get_option( 'no_maps_on_homepage' ) )
		) {
			wp_enqueue_script(
				'google-maps',
				'//maps.googleapis.com/maps/api/js?&sensor=true&key=' . Homeroom::get_option( 'google_maps_api_key' ),
				array( 'jquery' ), // this doesn't technically require jQuery, but the way I use it does, because I'm lazy
				1, // version
				true // footer
			);
		}
	}

	function wp_title( $title, $sep ) {
		global $page, $paged;

		if ( is_feed() )
			return $title;

		// Add the blog name
		$title .= get_bloginfo( 'name' );

		// Add the blog description for the home/front page.
		$site_description = get_bloginfo( 'description', 'display' );
		if ( $site_description && ( is_home() || is_front_page() ) )
			$title .= " $sep $site_description";

		// Add a page number if necessary:
		if ( $paged >= 2 || $page >= 2 )
			$title .= " $sep " . sprintf( __( 'Page %s', '_s' ), max( $paged, $page ) );

		return $title;
	}

	function body_class( $classes ) {
		$classes[] = 'default-menu';

		if ( is_search() && 'masonry' == Homeroom::get_option( 'search_results_view' ) ) {
			$classes[] = 'masonry';
		}

		return $classes;
	}

	/*
	 * Mark up your posts as if for the single page (H1 = post title, top heading in content is H2)
	 * This code will automatically "downgrade" your headings on other pages, assuming H1 is your site,
	 * H2 is the post title, H3 is the first content heading.
	 */
	function dynamic_headings( $content ) {
		 if ( is_singular() )
			 return $content;

		 $content = str_replace( array( '<h5', '</h5>' ), array( '<h6', '</h6>' ), $content );
		 $content = str_replace( array( '<h4', '</h4>' ), array( '<h5', '</h5>' ), $content );
		 $content = str_replace( array( '<h3', '</h3>' ), array( '<h4', '</h4>' ), $content );
		 $content = str_replace( array( '<h2', '</h2>' ), array( '<h3', '</h3>' ), $content );

		 return $content;
	}

	function oembed_helper( $content ) {
		if ( !class_exists( 'WP_oEmbed' ) )
			require_once ABSPATH . WPINC . '/class-oembed.php';

		$oembed = _wp_oembed_get_object(); // I know, it's private, but it provides a Singleton
		foreach ( $oembed->providers as $matchmask => $data ) {
			list( $providerurl, $regex ) = $data;

			// Turn the asterisk-type provider URLs into regex
			if ( !$regex ) {
				$matchmask = '#' . str_replace( '___wildcard___', '(.+)', preg_quote( str_replace( '*', '___wildcard___', $matchmask ), '#' ) ) . '#i';
				$matchmask = preg_replace( '|^#http\\\://|', '#https?\://', $matchmask );
			}

			// Monkey-patch the regular expression to not match stuff that's quoted or next to tags
			$boundary = substr( $matchmask, 0, 1 );
			$matchmask = $boundary . '[^"\'>]' . str_replace( $boundary, '[^"\'>]' . $boundary, substr( $matchmask, 1 ) );
			if ( preg_match_all( $matchmask, $content, $matches ) ) {
				$url = $matches[0][0];
				if ( $loc = strpos( $url, '"' ) )
					$url = substr( $url, 0, $loc );
				$content .= "\n\n" . $url;
			}
		}

		return $content;
	}

	/**
	 * If this is a Page, then look for and list sub-pages at the end of the content
	 */
	function child_pages( $content ) {
		if ( !is_singular( 'page' ) )
			return $content;

		global $post;
		$add = '';
		$children = get_posts( array(
			'post_parent' => $post->ID,
			'post_type'   => 'page',
			'numberposts' => -1,
			'orderby'     => 'menu_order',
			'order'       => 'ASC',
		) );
		if ( $children ) {
			$add .= '<div class="sub-pages-container"><h2>' . esc_html( 'Sub-Pages', 'homeroom' ) . '</h2><ol>';
			foreach ( $children as $child ) {
				$add .= '<li><a href="' . get_permalink( $child->ID ) . '">' . get_the_title( $child->ID ) . '</a></li>';
			}
			$add .= '</ol></div>';
		}

		return $content . $add;
	}

	function post_flair_shortlink( $content ) {
		if ( !Homeroom::get_option( 'display_shortlink_in_flair' ) )
			return $content;

		$content .= "<div class='sharedaddy sd-block'><h3 class='sd-title'>" . esc_html__( 'Shortlink:', 'homeroom' ) . '</h3>';
		$content .= '<input type="text" readonly="readonly" value="' . esc_attr( wp_get_shortlink() ) . '" />';
		$content .= '</div>';
		return $content;
	}

	function contact_methods( $methods ) {
		$methods['twitter']    = __( 'Twitter Username',    'homeroom' );
		$methods['foursquare'] = __( 'Foursquare Username', 'homeroom' );
		$methods['instagram']  = __( 'Instagram Username',  'homeroom' );
		$methods['flickr']     = __( 'Flickr Username',     'homeroom' );
		$methods['delicious']  = __( 'Delicious Username',  'homeroom' );
		$methods['tripit']     = __( 'TripIt Username',     'homeroom' );

		// Alphabetize them. We're not animals.
		ksort( $methods );
		return $methods;
	}

	function get_possible_post_types() {
		// Determine the list of possible filters (all importers + "posts")
		$known_types = array( 'posts' );
		global $_keyring_importers;
		if ( !empty( $_keyring_importers ) ) {
			foreach ( $_keyring_importers as $service => $importer ) {
				if ( !empty( $importer->service ) && $importer->service->get_token() ) {
					$known_types[] = $service;
				}
			}
		}
		return $known_types;
	}

	function hide_twitter_replies( $query ) {
		if ( is_admin() )
			return;

		if ( ! Homeroom::get_option( 'hide_twitter_replies') )
			return;

		$twitter_reply_meta_query = array(
			'key'     => 'twitter_in_reply_to_user_id',
			'compare' => 'NOT EXISTS',
		);
		$meta_query = $query->get( 'meta_query' );
		if ( is_array( $query ) ) {
			$meta_query[] = $twitter_reply_meta_query;
		} else {
			$meta_query = array( $twitter_reply_meta_query );
		}
		$query->set( 'meta_query', $meta_query );
	}

	function hide_twitter_replies_adjacent( $where ) {
		if ( is_admin() )
			return;

		if ( ! Homeroom::get_option( 'hide_twitter_replies') )
			return;

		// @todo
		// filter join to include postmeta?
		// where no twitter_in_reply_to_user_id

		return $where;
	}

	function hide_foursquare_checkins( $query ) {
		if (
			! Homeroom::get_option( 'hide_checkins_on_home' )
		||
			is_admin()
		||
			is_singular()
		||
			is_tag()
		||
			is_category()
		)
			return;

		$obj = get_queried_object();
		if ( taxonomy_exists( 'keyring_services' ) && $obj && 'Foursquare' == $obj->name )
			return;

		$foursquare_tax_query = array(
			'taxonomy' => 'keyring_services',
			'terms'    => array( 'Foursquare' ),
			'operator' => 'NOT IN',
			'field'    => 'name',
		);

		$query->tax_query->queries[]    = $foursquare_tax_query;
   		$query->query_vars['tax_query'] = $query->tax_query->queries;
	}

	function collate_checkins() {
		// Don't want random maps appearing amongst our archives
		if ( !is_front_page() && !is_post_type_archive( 'status' ) )
			return;

		// Foursquare check-ins just aren't that useful, so we're only going to show them on
		// a single map, at MOST once per 24 hours.

		// First, get the datestamp from the post that was just rendered
		$previous = get_the_date( 'Y-m-d H:i:s', '', '', false );
		if ( !$this->multimap_start )
			$this->multimap_start = $previous;

		// Then check for the next post
		$next = homeroom_next_post();
		if ( !$next ) {
			// If we hit the end of the page, output another map unless the last thing we did was a map
			if ( !$this->just_did_map ) {
				if ( !$this->multimap_end )
					$this->multimap_end = $previous;
				$this->render_multipoint_map();
				$this->multimap_start = $this->multimap_end = false;
			}
			return;
		}
		homeroom_prev_post();

		$next     = $next->post_date; // Already in Y-m-d H:i:s
		$next     = explode( ' ', $next );
		$previous = explode( ' ', $previous );

		// Same day still? Bail.
		if ( $previous[0] == $next[0] )
			return;

		if ( substr( $this->last_map, 0, strpos( $this->last_map, ' ' ) ) == $previous[0] )
			return;

		// todo: add in logic to get us to 5am/handle gaps etc
		$this->multimap_end = $next[0] . ' ' . $next[1];
		$this->render_multipoint_map();
		$this->multimap_start = $this->multimap_end = false;
	}

	function get_daily_checkins() {
		// Temporarily remove the filter that prevents check-ins from showing normally
		remove_filter( 'pre_get_posts', array( $this, 'hide_foursquare_checkins' ) );

		// And instead filter things by a specific date range
		add_filter( 'posts_where', array( $this, 'multimap_posts_between' ) );
		$markers = get_posts( array(
			'suppress_filters' => false, // we need our filters
			'numberposts'      => 50, // put a limit to avoid killing maps
			'meta_query' => array(
				array(
					'key'     => 'geo_public',
					'value'   => '1',
					'compare' => '=',
				),
			),
			'tax_query' => array(
				array(
					'taxonomy' => 'keyring_services',
					'terms'    => array( 'Foursquare' ),
					'field'    => 'name',
				)
			),
		) );
		remove_filter( 'posts_where', array( $this, 'multimap_posts_between' ) );

		// Flip filters back to how they should be now that we have what we want
		add_filter( 'pre_get_posts', array( $this, 'hide_foursquare_checkins' ) );

		return $markers;
	}

	function render_multipoint_map() {
		if ( is_home() && Homeroom::get_option( 'no_maps_on_homepage' ) )
			return;

		if ( $this->map_markers = $this->get_daily_checkins() ) {
			echo '<article class="f-status">';
				get_template_part( 'once', 'status' );
				get_template_part( 'map', 'multipoint' );
			echo '</article>';
		}
	}

	function multimap_posts_between( $where = '' ) {
		global $wpdb;
		// The dates are actually "reversed" because blogs go back in time
		$where .= $wpdb->prepare(
			" AND ( `post_date` BETWEEN %s AND %s ) AND `post_date` < %s - INTERVAL %d HOUR",
			$this->multimap_end,
			$this->multimap_start,
			current_time( 'mysql' ),
			Homeroom::get_option( 'hide_checkins_for_hours' )
		);
		$this->multimap_start = $this->multimap_end;
		return $where;
	}

	public static function get_option( $name, $default = false ) {
		// @todo Temporarily hardcoded, see options.php
		// $options = get_option( 'homeroom_options' );
		$options = homeroom_options();
		if ( is_array( $options ) && isset( $options[$name] ) ) {
			return $options[$name];
		}

		return $default;
	}

	public static function update_option( $name, $value ) {
		// @todo Temporarily hardcoded, see options.php
		// $options = get_option( 'homeroom_options' );
		$options = homeroom_options();
		if ( ! is_array( $options ) ) {
			$options = array();
		}

		$options[$name] = $value;

		return update_option( 'homeroom_options', $options );
	}
}

// Welcome to Homeroom
$homeroom = new Homeroom;
