<?php
/**
 * The Template for displaying all single posts.
 *
 * @package Homeroom
 * @since Homeroom 1.0
 */

get_header(); ?>

		<div id="primary" class="site-content">
			<div id="content" role="main">
				<div id="timeline">

				<?php while ( have_posts() ) : the_post(); ?>

					<?php get_template_part( 'singular', get_post_format() ); ?>

					<?php
						// If comments are open or we have at least one comment, load up the comment template
						if ( comments_open() || '0' != get_comments_number() )
							comments_template( '', true );
					?>

					<?php homeroom_content_nav( 'nav-below' ); ?>

				<?php endwhile; // end of the loop. ?>

				</div><!-- #timeline -->
			</div><!-- #content -->
		</div><!-- #primary .site-content -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>