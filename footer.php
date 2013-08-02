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
	<footer id="colophon" class="site-footer" role="contentinfo">
		<?php if ( is_singular() ) : ?>
			<?php if ( is_active_sidebar( 'footer-wide' ) ) : ?>
				<div id="footer-wide">
					<?php dynamic_sidebar( 'footer-wide' ); ?>
				</div>
			<?php else : ?>
				<?php
				// If any of the footer-columns are active, then try to load them all
				if (
					is_active_sidebar( 'footer-left' )
				||
					is_active_sidebar( 'footer-center' )
				||
					is_active_sidebar( 'footer-right' )
				) : ?>
					<div id="footer-left" class="footer-col">
						<div class="footer-col-inner">
							<?php dynamic_sidebar( 'footer-left' ); ?>
						</div>
					</div>
					<div id="footer-center" class="footer-col">
						<div class="footer-col-inner">
							<?php dynamic_sidebar( 'footer-center' ); ?>
						</div>
					</div>
					<div id="footer-right" class="footer-col">
						<div class="footer-col-inner">
							<?php dynamic_sidebar( 'footer-right' ); ?>
						</div>
					</div>
				<?php endif; ?>
			<?php endif; ?>
			<div class="clearfix"></div>
		<?php endif; ?>
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
<?php wp_footer(); ?>
</body>
</html>