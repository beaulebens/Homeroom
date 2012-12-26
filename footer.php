<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the id=main div and all content after
 *
 * @package Homeroom
 * @since Homeroom 1.0
 */
?>

		</div><!-- #main -->

		<?php do_action( 'before_footer' ); ?>
		<footer id="colophon" class="site-footer shadow" role="contentinfo">
			<?php if ( is_active_sidebar( 'footer-wide' ) ) : ?>
				<div id="footer-wide">
					<?php dynamic_sidebar( 'footer-wide' ); ?>
				</div>
			<?php else : ?>
				<?php if ( is_active_sidebar( 'footer-1' ) ) : ?>
					<div id="footer-left" class="footer-col">
						<?php dynamic_sidebar( 'footer-1' ); ?>
					</div>
				<?php endif; ?>
				<?php if ( is_active_sidebar( 'footer-2' ) ) : ?>
					<div id="footer-center" class="footer-col">
						<?php dynamic_sidebar( 'footer-2' ); ?>
					</div>
				<?php endif; ?>
				<?php if ( is_active_sidebar( 'footer-3' ) ) : ?>
					<div id="footer-right" class="footer-col">
						<?php dynamic_sidebar( 'footer-3' ); ?>
					</div>
				<?php endif; ?>
			<?php endif; ?>
			<div class="clearfix"></div>
			<div id="footer-attribution">
				<?php
				echo apply_filters(
					'homeroom_attribution',
					sprintf(
						__( 'Powered by the %s for %s.' ),
						'<a href="http://dentedreality.com.au/projects/wp-theme-homeroom/">Homeroom theme</a>',
						'<a href="http://wordpress.org/">WordPress</a>'
					)
				);
				?>
			</div>
		</footer><!-- #colophon .site-footer -->
		<?php do_action( 'after_footer' ); ?>
	</div><!-- #page .hfeed .site -->
</div>
<?php wp_footer(); ?>
</body>
</html>