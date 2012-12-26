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

			<?php
			// Show in-page posting UI for logged in users who can post
			// @todo make this conditional on the theme option
			get_template_part( 'editor' );

			// Now display posts
			if ( have_posts() ) {

				do_action( 'before_loop' );

				while ( have_posts() ) {
					the_post();

					do_action( 'before_post' );

					/* Include the Post-Format-specific template for the content.
					 * If you want to overload this in a child theme then include a file
					 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
					 */
					get_template_part( 'content', get_post_format() );

					do_action( 'after_post' );
				}

				do_action( 'after_loop' );

				homeroom_content_nav( 'nav-below' );

			} else if ( current_user_can( 'edit_posts' ) ) {
				get_template_part( 'no-results', 'index' );
			}
			?>

			</div><!-- #content -->
		</div><!-- #primary .site-content -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>