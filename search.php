<?php
/**
 * The template for displaying Search Results pages.
 *
 * @package Homeroom
 * @since Homeroom 1.0
 */

if ( 'masonry' == Homeroom::get_option( 'search_results_view' ) ) {
	add_action( 'wp_enqueue_scripts', function() {
		wp_enqueue_script( 'jquery-masonry' );
	} );
}

get_header(); ?>

		<section id="primary" class="site-content">
			<div id="content" role="main">

			<?php if ( have_posts() ) : ?>

				<header class="page-header">
					<h1 class="page-title">
						<?php
						_e( 'Search for: ', 'homeroom' );
						get_template_part( 'searchform' );
						?>
					</h1>
				</header><!-- .page-header -->

				<div id="masonry">
				<?php while ( have_posts() ) : the_post(); ?>
					<?php get_template_part( 'content', 'search' ); ?>
				<?php endwhile; ?>
				</div>

				<div class="clearfix"></div>

				<?php homeroom_content_nav( 'nav-below' ); ?>

			<?php else : ?>

				<?php get_template_part( 'no-results', 'search' ); ?>

			<?php endif; ?>

			</div><!-- #content -->
		</section><!-- #primary .site-content -->

<?php get_footer(); ?>