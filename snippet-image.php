<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<div class="entry-content">
		<?php

		// @todo check for password protection

		// Get the content and extract the first img tag src
		$content = apply_filters( 'the_content', get_the_content() );
		if ( preg_match( '/<img[^>]*src=[\'"][^\'"]+[\'"][^>]*>/is', $content, $matches ) ) {
			echo '<a href="' . get_permalink() . '">' . $matches[0] . '</a>';

			// Now output a panel which will overlay the image onHover, containing further details
			$remaining_content = str_replace( $matches[0], '', $content );
		} else {
			$remaining_content = $content;
		}
		if (
			trim( strip_tags( $remaining_content ) )
		||
			'instagram' != get_post_meta( get_the_ID(), 'keyring_service', true )
		) : ?>
			<div class="image-overlay">
				<header class="entry-header">
					<div class="image-overlay-content">
						<?php
						if ( 'instagram' != get_post_meta( get_the_ID(), 'keyring_service', true ) )
							echo '<h1 class="entry-title">' . get_the_title() . '</h1>';

						echo force_balance_tags( $remaining_content );
						?>
					</div>
				</header><!-- .entry-header -->
			</div>
		<?php endif; ?>

		<footer class="entry-meta">
			<?php homeroom_permalink_datestamp(); ?>
			<?php edit_post_link( __( 'Edit', 'homeroom' ), '<span class="edit-link">', '</span>' ); ?>
		</footer><!-- .entry-meta -->

		<?php homeroom_tags_list(); ?>

		<div class="clearfix"></div>
	</div><!-- .entry-content -->

	<?php get_template_part( 'in-page-map' ); ?>

</div>