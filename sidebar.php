<?php
/**
 * The Sidebar containing the main widget areas.
 *
 * @package Homeroom
 * @since Homeroom 1.0
 */
?>
		<div id="secondary" class="widget-area" role="complementary">
			<div id="secondary-content">

				<?php do_action( 'before_sidebar' ); ?>

				<?php if ( ! dynamic_sidebar( 'sidebar-1' ) ) : ?>
					<style>
					#content {
						margin: 0;
					}
					</style>
				<?php endif; // end sidebar widget area ?>

				<?php do_action( 'after_sidebar' ); ?>

			</div>
		</div><!-- #secondary .widget-area -->
