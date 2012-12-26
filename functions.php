<?php
/**
 * Homeroom functions and definitions
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
	$content_width = 640; /* pixels */

/**
 * Define your GMaps API key in wp-config or something to override this one.
 * If you don't define one somewhere, all maps functionality will fail.
 * https://code.google.com/apis/console/
 */
define( 'GOOGLE_MAPS_API_KEY', 'AIzaSyAeH_bDOuGPtNuji2Wt--4YSQ3MymIbpWA' );
defined( 'GOOGLE_MAPS_API_KEY' ) or define( 'GOOGLE_MAPS_API_KEY', false );

/**
 * Implement the Custom Header feature
 */
require( get_template_directory() . '/inc/custom-header.php' );

if ( ! function_exists( 'homeroom_setup' ) ):
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 *
 * @since Homeroom 1.0
 */
function homeroom_setup() {

	/**
	 * Custom template tags for this theme.
	 */
	require( get_template_directory() . '/inc/template-tags.php' );

	/**
	 * Custom functions that act independently of the theme templates
	 */
	//require( get_template_directory() . '/inc/tweaks.php' );

	/**
	 * Custom Theme Options
	 */
	require( get_template_directory() . '/inc/theme-options/theme-options.php' );

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
		'default-color' => 'eee',
	) );

	/**
	 * This theme uses wp_nav_menu() in one location.
	 */
	register_nav_menus( array(
		'main' => __( 'Main Site Menu', 'homeroom' ),
	) );

	/**
	 * Add support for all the post formats we're using
	 */
	add_theme_support( 'post-formats', array( 'aside', 'status', 'link', 'image' ) );

	/**
	 * Infinite Scroll, via Jetpack
	 * @see http://jetpack.me/support/infinite-scroll/
	 */
	add_theme_support( 'infinite-scroll', array(
		'container'      => 'content',
		'footer'         => 'colophon',
		'type'           => 'click',
		'footer_widgets' => true,
		'posts_per_page' => get_option( 'posts_per_page' ),
) );
}
endif; // homeroom_setup
add_action( 'after_setup_theme', 'homeroom_setup' );

/**
 * Register widgetized area and update sidebar with default widgets
 *
 * @since Homeroom 1.0
 */
function homeroom_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Sidebar', 'homeroom' ),
		'id'            => 'sidebar-1',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => "</aside>",
		'before_title'  => '<h1 class="widget-title">',
		'after_title'   => '</h1>',
	) );

	register_sidebar( array(
		'name'          => __( 'Footer (Full Width)', 'homeroom' ),
		'id'            => 'footer-wide',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => "</aside>",
		'before_title'  => '<h1 class="widget-title">',
		'after_title'   => '</h1>',
	) );

	register_sidebar( array(
		'name'          => __( 'Footer (Left)', 'homeroom' ),
		'id'            => 'footer-1',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => "</aside>",
		'before_title'  => '<h1 class="widget-title">',
		'after_title'   => '</h1>',
	) );

	register_sidebar( array(
		'name'          => __( 'Footer (Center)', 'homeroom' ),
		'id'            => 'footer-2',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => "</aside>",
		'before_title'  => '<h1 class="widget-title">',
		'after_title'   => '</h1>',
	) );

	register_sidebar( array(
		'name'          => __( 'Footer (Right)', 'homeroom' ),
		'id'            => 'footer-3',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => "</aside>",
		'before_title'  => '<h1 class="widget-title">',
		'after_title'   => '</h1>',
	) );
}
add_action( 'widgets_init', 'homeroom_widgets_init' );

/**
 * Enqueue scripts and styles
 */
function homeroom_scripts() {
	global $post;

	wp_enqueue_style( 'style', get_stylesheet_uri() );
	wp_enqueue_style( 'homeroom-css', dirname( get_stylesheet_uri() ) . '/homeroom.css' );

	wp_enqueue_script( 'small-menu', get_template_directory_uri() . '/js/small-menu.js', array( 'jquery' ), '20120206', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	// Global JS file for all additional functionality
	wp_enqueue_script( 'keyboard-image-navigation', get_template_directory_uri() . '/js/homeroom.js', array( 'jquery' ), '20120202' );

	// Embedded Tweets, for non-mobile only
	// See https://dev.twitter.com/docs/embedded-tweets
	if (
		!function_exists( 'jetpack_is_mobile' )
	||
		!jetpack_is_mobile()
	) {
		wp_enqueue_script(
			'twitter-embed',
			'//platform.twitter.com/widgets.js',
			false, // no deps
			false, // version
			true // footer
		);
	}

	// Google Maps API
	if ( false != GOOGLE_MAPS_API_KEY ) {
		wp_enqueue_script(
			'google-maps',
			'//maps.googleapis.com/maps/api/js?&sensor=true&key=' . GOOGLE_MAPS_API_KEY,
			array( 'jquery' ), // I am lazy, so I'm going to require jQuery -- don't do this :)
			1, // version
			true // footer
		);
	}
}
add_action( 'wp_enqueue_scripts', 'homeroom_scripts' );

/**
 * Add a class to the body to make the menu non-fixed by default.
 * We can dynamically change this via JS to 'fixed-menu' if we
 * want the menu to fix to the top of the viewport.
 */
add_action( 'body_class', function( $classes ){
	$classes[] = 'default-menu';
	return $classes;
});

/**
 * We register a few extra contact methods just because it makes sense in a social context,
 * but also because we can use them for fallbacks in building URLs etc.
 */
function homeroom_contact_methods() {
	$methods['twitter']    = 'Twitter'; // Intentionally not translating these because they're product names
	$methods['foursquare'] = 'Foursquare';
	$methods['instagram']  = 'Instagram';
	$methods['flickr']     = 'Flickr';
	$methods['delicious']  = 'Delicious';

	// Alphabetize them. We're not animals.
	ksort( $methods );
	return $methods;
}
add_filter( 'user_contactmethods', 'homeroom_contact_methods' );

/**
 * We are going to remove all Foursquare checkins from normal display,
 * then we'll add them back in with a custom query that just loads them
 * for each 24 hour period. Allow them in for search results.
 */
function homeroom_hide_status_posts( &$wp_query ) {
	if ( is_admin() || is_search() )
		return;

	$post_format_tax_query = array(
		'taxonomy' => 'post_format',
		'field'    => 'slug',
		'terms'    => 'post-format-status', // Foursquare saved as status
		'operator' => 'NOT IN'
	);
	$tax_query = $wp_query->get( 'tax_query' );
	if ( is_array( $tax_query ) ) {
		$tax_query = $tax_query + $post_format_tax_query;
	} else {
		$tax_query = array( $post_format_tax_query );
	}
	$wp_query->set( 'tax_query', $tax_query );
}
add_action( 'pre_get_posts', 'homeroom_hide_status_posts' );

/**
 * This just flags that the last thing we did was not actually a map. Since this is
 * on before_post, that means we're about to output a post, so by the time after_post
 * fires, then we actually just output a post.
 */
function homeroom_before_post() {
	global $homeroom_just_did_map;
	$homeroom_just_did_map = false;
}
add_action( 'before_post', 'homeroom_before_post' );

/**
 * Thanks to some custom hooks in this theme, we have somewhere useful to check
 * and see if we're crossing over a time-threshold that should trigger a query
 * to get a map of today's movements.
 */
function homeroom_collate_checkins() {
	global $homeroom_just_did_map, $homeroom_last_map, $homeroom_multimap_start, $homeroom_multimap_end;

	// Foursquare check-ins just aren't that useful, so we're only going to show them on
	// a single map, at MOST once per 24 hours.

	// First, get the datestamp from the post that was just rendered
	$previous = get_the_date( 'Y-m-d H:i:s', '', '', false );
	if ( !$homeroom_multimap_start )
		$homeroom_multimap_start = $previous;

	// Then check for the next post
	$next = homeroom_next_post();
	if ( !$next ) {
		// If we hit the end of the page, output another map unless the last thing we did was a map
		if ( !$homeroom_just_did_map ) {
			if ( !$homeroom_multimap_end )
				$homeroom_multimap_end = $previous;
			if ( $GLOBALS['homeroom_map_markers'] = homeroom_get_daily_checkins() ) {
				echo '<article class="shadow f-status">';
				get_template_part( 'multimap' );
				echo '</article>';
			}
			$homeroom_multimap_start = $homeroom_multimap_end = false;
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

	if ( substr( $homeroom_last_map, 0, strpos( $homeroom_last_map, ' ' ) ) == $previous[0] )
		return;

	// todo: add in logic to get us to 5am/handle gaps etc
	$homeroom_multimap_end = $next[0] . ' ' . $next[1];
	if ( $GLOBALS['homeroom_map_markers'] = homeroom_get_daily_checkins() ) {
		echo '<article class="shadow f-status">';
		get_template_part( 'multimap' );
		echo '</article>';
	}
	$homeroom_multimap_start = $homeroom_multimap_end = false;
}
add_action( 'after_post', 'homeroom_collate_checkins' );

/**
 * Grab a day's worth of checkins from the DB.
 * @uses homeroom_multimap_posts_between()
 * @return Array containing located Posts (checkins), false if none.
 */
function homeroom_get_daily_checkins() {
	// Temporarily remove the filter that prevents check-ins from showing normally
	remove_filter( 'pre_get_posts', 'homeroom_hide_status_posts' );

	// And instead filter things by a specific date range
	add_filter( 'posts_where', 'homeroom_multimap_posts_between' );
	$markers = get_posts( array(
		'suppress_filters' => false, // we need our filters
		'numberposts'      => -1, // all of the things
		'meta_query' => array(
			array(
				'key'     => 'keyring_service',
				'value'   => 'foursquare',
				'compare' => '=',
			),
			array(
				'key'     => 'geo_public',
				'value'   => '1',
				'compare' => '=',
			),
		),
		'tax_query'        => array( array(
			'taxonomy' => 'post_format',
			'field'    => 'slug',
			'terms'    => array( 'post-format-status' ), // Check-ins are marked as a 'status'
			'operator' => 'IN',
		) ),
	) );

	// Flip filters back to how they should be now that we have what we want
	remove_filter( 'posts_where', 'homeroom_multimap_posts_between' );
	add_filter( 'pre_get_posts', 'homeroom_hide_status_posts' );

	return $markers;
}

/**
 * Restrict WP_Query to a date range, based on the $homeroom_multimap_* globals.
 * Used in multimap.php
 */
function homeroom_multimap_posts_between( $where = '' ) {
	global $wpdb, $homeroom_multimap_start, $homeroom_multimap_end;
	// The dates are actually "reversed" because blogs go back in time
	// @todo exclude based on option
	// @todo optionally don't do this at all
	$where .= $wpdb->prepare( " AND ( `post_date` BETWEEN %s AND %s ) AND `post_date` < %s - INTERVAL 2 HOUR", $homeroom_multimap_end, $homeroom_multimap_start, current_time( 'mysql' ) );
	$homeroom_multimap_start = $homeroom_multimap_end;
	return $where;
}

/**
 * We use Post Formats a lot, let's add that as a filter on the All Posts page
 */
function homeroom_restrict_manage_posts( $checks = true, $all_label = false ) {
	if ( $checks ) {
		if ( !current_theme_supports( 'post-formats' ) )
			return;

		if ( 'post' !== get_current_screen()->post_type )
			return;
	}

	$all_label = !$all_label ? __( 'All Formats', 'homeroom' ) : $all_label;

	$post_formats = get_theme_support( 'post-formats' );
	if ( is_array( $post_formats[0] ) ) :
	?><select name="format" id="format">
	<option value="0"><?php _e( $all_label, 'homeroom' ); ?></option>
	<?php foreach ( $post_formats[0] as $format ): ?>
	<option<?php selected( isset( $_REQUEST['format'] ) && $_REQUEST['format'] == $format ); ?> value="<?php echo esc_attr( $format ); ?>"><?php echo esc_html( get_post_format_string( $format ) ); ?></option>
	<?php endforeach; ?>
	</select><?php
	endif;
}
add_action( 'restrict_manage_posts', 'homeroom_restrict_manage_posts' );

function homeroom_manage_posts_formats( &$wp_query ) {
	if ( !is_admin() )
		return;

	if ( function_exists( 'get_current_screen' ) && $screen = get_current_screen() ) {
		if ( is_object( $screen ) && 'post' !== $screen->post_type )
			return;
	}

	if ( empty( $_REQUEST['format'] ) )
		return;

	$post_format_tax_query = array(
		'taxonomy' => 'post_format',
		'field'    => 'slug',
		'terms'    => 'post-format-' . esc_attr( $_REQUEST['format'] ),
		'operator' => 'IN'
	);
	$tax_query = $wp_query->get( 'tax_query' );
	if ( is_array( $tax_query ) ) {
		$tax_query = $tax_query + $post_format_tax_query;
	} else {
		$tax_query = array( $post_format_tax_query );
	}
	$wp_query->set( 'tax_query', $tax_query );
}
add_action( 'pre_get_posts', 'homeroom_manage_posts_formats' );

/**
 * We add some extra settings to Settings > Discussion, because Homeroom introduces some new
 * functionality in that area.
 */
function homeroom_discussion_settings() {
 	register_setting( 'discussion', 'highlight_commenter_emails', 'highlight_commenter_emails_validate' );
 	add_settings_field( 'highlight_commenter_emails', __( 'Highlight user comments', 'homeroom' ), 'homeroom_comment_emails', 'discussion', 'default' );
}
add_action( 'admin_init', 'homeroom_discussion_settings' );

function homeroom_comment_emails() {
	?><input name="highlight_commenter_emails" type="text" id="highlight_commenter_emails" value="<?php echo esc_attr( get_option( 'highlight_commenter_emails' ) ); ?>" class="regular-text" />
	<p class="description"><?php echo esc_html( __( 'Enter a list of email addresses, separated by spaces, to highlight any comments made from those users.', 'homeroom' ) ); ?></p><?php
}

function highlight_commenter_emails_validate( $val ) {
	$val = explode( ' ', $val );
	$out = array();
	foreach ( (array) $val as $email ) {
		if ( is_email( $email ) )
			$out[] = strtolower( $email );
	}
	return implode( ' ', $out );
}


// @todo make conditional on if the editor is being loaded
function homeroom_editor_auto_complete_tags_script() {
	wp_enqueue_script( 'suggest' );
}
add_action( 'wp_enqueue_scripts', 'homeroom_editor_auto_complete_tags_script' );

function homeroom_wp_title( $title, $sep ) {
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
add_filter( 'wp_title', 'homeroom_wp_title', 10, 2 );

function homeroom_masonry_class( $class ) {
	if ( is_search() || is_archive() )
		$class[] = 'masonry';
	return $class;
}
add_filter( 'body_class', 'homeroom_masonry_class' );