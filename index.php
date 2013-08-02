<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Homeroom
 * @since Homeroom 1.0
 */

get_header(); ?>

		<div id="primary" class="site-content">
			<div id="content" role="main">
				<div id="timeline">

					<?php
					// Show in-page posting UI for logged in users who can post
					get_template_part( 'editor' );

					// We're using this file to handle a bunch of archives
					if ( !is_home() ) {
						echo '<header class="page-header">';

							if ( is_category() ) {
								echo '<h1 class="page-title">';
								echo single_cat_title( '', false );

								if ( $url = get_edit_term_link( get_queried_object_id(), 'category', 'post' ) ) {
									echo '<span class="edit"><a href="' . esc_url( $url ) . '">' . __( 'Edit', 'homeroom' ) . '</a></span>';
								}

								echo '</h1>';

								if ( $desc = category_description() ) {
									echo  '<div class="tax-description">' . $desc . '</div>';
								}
							} else if ( is_tag() ) {
								echo '<h1 class="page-title"><span class="hash">#</span>';
								echo single_tag_title( '', false );

								if ( $url = get_edit_term_link( get_queried_object_id(), 'post_tag', 'post' ) ) {
									echo '<span class="edit"><a href="' . esc_url( $url ) . '">' . __( 'Edit', 'homeroom' ) . '</a></span>';
								}

								echo '</h1>';

								if ( $desc = tag_description() ) {
									echo  '<div class="tax-description">' . $desc . '</div>';
								}
							} else if ( is_author() ) {
								the_post();
								echo '<h1 class="page-title">';
								printf( __( 'Posts by %s', 'homeroom' ), '<span class="vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( "ID" ) ) ) . '" title="' . esc_attr( get_the_author() ) . '" rel="me">' . get_the_author() . '</a></span>' );
								echo '</h1>';
								rewind_posts();

								if ( $desc = get_the_author_meta( 'description' ) )
									echo '<p>' . $desc . '</p>';
							} else if ( is_day() || is_month() || is_year() ) {
								echo '<h1 class="page-title">';
								if ( is_day() )
									printf( __( 'Posts from %s', 'homeroom' ), '<span>' . get_the_date() . '</span>' );
								else if ( is_month() )
									printf( __( 'Posts from %s', 'homeroom' ), '<span>' . get_the_date( 'F Y' ) . '</span>' );
								else if ( is_year() )
									printf( __( 'Posts from %s', 'homeroom' ), '<span>' . get_the_date( 'Y' ) . '</span>' );
								echo '</h1>';
							}

						echo '</header>';
					}

					// Now display posts
					if ( have_posts() ) {

						do_action( 'before_loop' );

						while ( have_posts() ) {
							the_post();

							/* Include the Post-Format-specific template for the content.
							 * If you want to overload this in a child theme then include a file
							 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
							 */
							get_template_part( 'content', get_post_format() );
						}

						do_action( 'after_loop' );

						// homeroom_content_nav( 'nav-below' );

					} else if ( current_user_can( 'edit_posts' ) ) {
						get_template_part( 'no-results', 'index' );
					}
					?>

					<?php homeroom_content_nav( 'nav-below' ); ?>

				</div><!-- #timeline -->

			</div><!-- #content -->
		</div><!-- #primary .site-content -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>