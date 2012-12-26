<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features
 *
 * @package Homeroom
 * @since Homeroom 1.0
 */

if ( ! function_exists( 'homeroom_content_nav' ) ):
/**
 * Display navigation to next/previous pages when applicable
 *
 * @since Homeroom 1.0
 */
function homeroom_content_nav( $nav_id ) {
	global $wp_query;

	$nav_class = 'site-navigation paging-navigation';
	if ( is_single() )
		$nav_class = 'site-navigation post-navigation';

	?>
	<nav role="navigation" id="<?php echo $nav_id; ?>" class="<?php echo $nav_class; ?>">
		<h1 class="assistive-text"><?php _e( 'Post navigation', 'homeroom' ); ?></h1>

	<?php if ( is_single() ) : // navigation links for single posts ?>

		<?php previous_post_link( '<div class="nav-previous">%link</div>', '<span class="meta-nav">' . _x( '&larr;', 'Previous post link', 'homeroom' ) . '</span> %title' ); ?>
		<?php next_post_link( '<div class="nav-next">%link</div>', '%title <span class="meta-nav">' . _x( '&rarr;', 'Next post link', 'homeroom' ) . '</span>' ); ?>

	<?php elseif ( $wp_query->max_num_pages > 1 && ( is_home() || is_archive() || is_search() ) ) : // navigation links for home, archive, and search pages ?>

		<?php if ( get_next_posts_link() ) : ?>
		<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'homeroom' ) ); ?></div>
		<?php endif; ?>

		<?php if ( get_previous_posts_link() ) : ?>
		<div class="nav-next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'homeroom' ) ); ?></div>
		<?php endif; ?>

	<?php endif; ?>

	</nav><!-- #<?php echo $nav_id; ?> -->
	<?php
}
endif; // homeroom_content_nav

if ( ! function_exists( 'homeroom_comment' ) ) :
/**
 * Template for comments and pingbacks.
 *
 * Used as a callback by wp_list_comments() for displaying the comments.
 *
 * @since Homeroom 1.0
 */
function homeroom_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;

	switch ( $comment->comment_type ) :
		case 'pingback' :
		case 'trackback' :
		case 'like' :
	?>
	<li class="comment-<?php echo esc_attr( $comment->comment_type ); ?>" id="li-comment-<?php comment_ID(); ?>">
		<p><?php comment_author_link(); ?><?php edit_comment_link( __( '(Edit)', 'homeroom' ), ' ' ); ?></p>
	<?php
			break;

		default :
			// Big Gravatars for top-level, smaller for replies
			$avatar_size = $depth > 1 ? 50 : 70;

			// Check if this comment was made by a highlighted user, and add class if so
			$commenter_class = '';
			if (
				!empty( $comment->comment_author_email )
			&&
				in_array(
					strtolower( $comment->comment_author_email ),
					explode( ' ', get_option( 'highlight_commenter_emails' ) )
				)
			) {
				$commenter_class = 'byhighlighteduser';
			}
	?>
	<li <?php comment_class( $commenter_class ); ?> id="li-comment-<?php comment_ID(); ?>">
		<article id="comment-<?php comment_ID(); ?>" class="comment">
			<div class="comment-author-avatar shadow"><?php echo get_avatar( $comment, $avatar_size ); ?></div>

			<div class="comment-wrap shadow">
				<footer>
					<div class="comment-author vcard">
						<?php printf( __( '%s <span class="says">says:</span>', 'homeroom' ), sprintf( '<cite class="fn">%s</cite>', get_comment_author_link() ) ); ?>
					</div><!-- .comment-author .vcard -->
					<?php if ( $comment->comment_approved == '0' ) : ?>
						<em><?php _e( 'Your comment is awaiting moderation.', 'homeroom' ); ?></em>
						<br />
					<?php endif; ?>

					<div class="comment-meta commentmetadata">
						<a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>"><time pubdate datetime="<?php comment_time( 'c' ); ?>">
						<?php
							/* translators: 1: date, 2: time */
							printf( __( '%1$s at %2$s', 'homeroom' ), get_comment_date(), get_comment_time() ); ?>
						</time></a>
						<?php edit_comment_link( __( '(Edit)', 'homeroom' ), ' ' );
						?>
					</div><!-- .comment-meta .commentmetadata -->
				</footer>

				<div class="comment-content"><?php comment_text(); ?></div>

				<div class="reply">
					<?php comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
				</div><!-- .reply -->
			</div>
		</article><!-- #comment-## -->
	<?php
			break;
	endswitch;
}
endif; // ends check for homeroom_comment()

if ( ! function_exists( 'homeroom_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time and author.
 *
 * @since Homeroom 1.0
 */
function homeroom_posted_on() {
	printf( __( 'Posted on <a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date" datetime="%3$s" pubdate>%4$s</time></a><span class="byline"> by <span class="author vcard"><a class="url fn n" href="%5$s" title="%6$s" rel="author">%7$s</a></span></span>', 'homeroom' ),
		esc_url( get_permalink() ),
		esc_attr( get_the_time() ),
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() ),
		esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
		esc_attr( sprintf( __( 'View all posts by %s', 'homeroom' ), get_the_author() ) ),
		esc_html( get_the_author() )
	);
}
endif;


/**
 * Returns true if a blog has more than 1 category
 *
 * @since Homeroom 1.0
 */
function homeroom_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'all_the_cool_cats' ) ) ) {
		// Create an array of all the categories that are attached to posts
		$all_the_cool_cats = get_categories( array(
			'hide_empty' => 1,
		) );

		// Count the number of categories that are attached to the posts
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'all_the_cool_cats', $all_the_cool_cats );
	}

	if ( '1' != $all_the_cool_cats ) {
		// This blog has more than 1 category so homeroom_categorized_blog should return true
		return true;
	} else {
		// This blog has only 1 category so homeroom_categorized_blog should return false
		return false;
	}
}

/**
 * Flush out the transients used in homeroom_categorized_blog
 *
 * @since Homeroom 1.0
 */
function homeroom_category_transient_flusher() {
	// Like, beat it. Dig?
	delete_transient( 'all_the_cool_cats' );
}
add_action( 'edit_category', 'homeroom_category_transient_flusher' );
add_action( 'save_post', 'homeroom_category_transient_flusher' );

/**
 * Gasp. This is outrageous. I'm giving us the ability to look-ahead in the loop and then come back
 * to the current one if we don't need to do anything special. Use this power wisely.
 *
 * Use it to peak at the next post, then we can optionally setup_postdata() or whatever and go from there.
 * @return WP_Post
 */
function homeroom_prev_post() {
	global $wp_query;
	if ( $wp_query->current_post > 0 )
		$wp_query->current_post--;
	$wp_query->post = $wp_query->posts[$wp_query->current_post];
	return $wp_query->post;
}
function homeroom_next_post() {
	global $wp_query;
	if ( $wp_query->current_post + 1 < $wp_query->post_count )
		return $wp_query->next_post();
	return false;
}

/**
 * Output a permalink with some added metadata and stuff.
 * @param  String $url Custom permalink to use, if left as false then the current post's permalink will be used.
 * @param  Mixed $class String or Array of classes to apply to the A HREF created.
 * @return void. Outputs the permalink immediately.
 */
function homeroom_permalink_datestamp( $url = false, $class = false, $date = false, $output = true ) {
	$url = $url ? $url : get_permalink();
	if ( $date ) {
		$datetime = gmdate( get_option( 'time_format' ) . ', ' . get_option( 'date_format' ), strtotime( $date ) );
		$dt = explode( ' ', $date );
		$time = $dt[1];
		$date = $dt[0];
	} else {
		$time = get_the_time();
		$date = get_the_date( 'c' );
		$datetime = get_post_time( get_option( 'time_format' ) . ', ' . get_option( 'date_format' ) );
	}
	$out = sprintf( '<a href="%1$s" title="%2$s" rel="bookmark" data-datetime="%3$s"%4$s>%5$s</a>',
		esc_url( $url ),
		esc_attr( $time ),
		esc_attr( $date ),
		( $class ? ' class="' . esc_attr( implode( ' ', (array) $class ) ) . '"' : '' ),
		esc_html( $datetime )
	);

	if ( $output )
		echo $out;
	return $out;
}

/**
 * Quick helper to render a Google Map right here, right now. Pass it a lat/long to add a
 * marker at that point, or else it will get the details from the current post. If the geo
 * is marked as not being public, then it will bail unless you set $force = true.
 * Assumes that the Google Maps JS code was already enqueued.
 * No InfoWindow/clickables/anything fancy, just a marker.
 * Based heavily on multimap.php
 * @param  geo $lat Latitude of the marker to add to the map
 * @param  geo $long Longitude of the marker to add to the map
 * @param  boolean $long Force render the map, even if the geo data for the current post is marked as non-public? Ignored if you pass in lat/long
 * @return void
 */
function homeroom_render_map( $lat = false, $long = false, $force = false ) {
	if ( !$lat && !$long ) {
		global $post;
		if ( 0 === get_post_meta( $post->ID, 'geo_public', true ) && !$force )
			return;
		$lat = get_post_meta( $post->ID, 'geo_latitude', true );
		$long = get_post_meta( $post->ID, 'geo_longitude', true );
		$id = $post->ID;
	} else {
		$id = mt_rand( 0, 1000 );
	}
	$map_id = 'm' . md5( microtime() . ":$lat:$long" );
	?><div id="gmap_<?php echo esc_js( $map_id ); ?>" class="google-map single-point"></div>
	<script type="text/javascript">
	jQuery(document).ready(function(){
		var gmap_<?php echo esc_js( $map_id ); ?> = {
			positions : {
				<?php echo esc_js( $id ); ?> : new google.maps.LatLng( '<?php echo $lat; ?>', '<?php echo $long; ?>' ),
			},
			bounds : new google.maps.LatLngBounds(), // empty for now, we'll dynamically extend it later
			map : new google.maps.Map(
				document.getElementById( 'gmap_<?php echo esc_js( $map_id ); ?>' ),
				{
					mapTypeId: google.maps.MapTypeId.ROADMAP,
					center: new google.maps.LatLng( 0, 0 ),
					zoom: 15 // Seems to be a good zoom for a single point
				}
			),
			markers : {},
		}; // end of gmap

		// Extend the bounds of interest based on our positions
		for ( var m in gmap_<?php echo esc_js( $map_id ); ?>.positions ) {
			gmap_<?php echo esc_js( $map_id ); ?>.bounds.extend( gmap_<?php echo esc_js( $map_id ); ?>.positions[m] );
		}

		// Render markers
		for ( var m in gmap_<?php echo esc_js( $map_id ); ?>.positions ) {
			gmap_<?php echo esc_js( $map_id ); ?>.markers[m] = new google.maps.Marker( {
				clickable: true,
				map : gmap_<?php echo esc_js( $map_id ); ?>.map,
				position : gmap_<?php echo esc_js( $map_id ); ?>.positions[m]
			} );
		}

		// Redraw map to fit our new marker-based bounds
		gmap_<?php echo esc_js( $map_id ); ?>.map.setCenter( gmap_<?php echo esc_js( $map_id ); ?>.positions[<?php echo esc_js( $id ); ?>] );
	});
	</script><?php
}

function homeroom_tags_list() {
	$tags_list = get_the_tag_list( '<span class="hash">#</span>', __( ' <span class="hash">#</span>', 'homeroom' ) );
	if ( $tags_list ) : ?>
		<div class="tag-links">
			<?php echo $tags_list; ?>
		</div>
	<?php
	endif;
}

