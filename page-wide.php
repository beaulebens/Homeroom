<?php
/**
 * Template Name: Wide Page (no sidebar)
 *
 * The template for displaying pages with more content, or
 * where you don't need/want a sidebar
 *
 * @package Homeroom
 * @since Homeroom 1.0
 */

get_header(); ?>

		<div id="primary" class="site-content">
			<div id="content" role="main">
				<div id="timeline">

					<?php while ( have_posts() ) : the_post(); ?>

						<?php get_template_part( 'singular', 'page' ); ?>

						<?php get_template_part( 'related-posts' ); ?>

						<?php comments_template( '', true ); ?>

					<?php endwhile; // end of the loop. ?>

				</div><!-- #timeline -->
			</div><!-- #content -->
		</div><!-- #primary .site-content -->

<?php get_footer(); ?>