<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<div class="entry-content">
		<div class="image-grouping">
			<?php

			// @todo check for password protection

			$keyring_service = get_post_meta( get_the_ID(), 'keyring_service', true );

			// Get the content and extract the first img tag src
			$content = apply_filters( 'the_content', get_the_content() );
			if ( preg_match( '/<img[^>]*src=[\'"][^\'"]+[\'"][^>]*>/is', $content, $matches ) ) {
				echo '<a href="' . esc_url( get_permalink() ) . '">' . $matches[0] . '</a>';

				// Now output a panel which will overlay the image onHover, containing further details
				$remaining_content = str_replace( $matches[0], '', $content );
			} else {
				$remaining_content = $content;
			}
			if (
				trim( strip_tags( $remaining_content ) )
			||
				'instagram' != $keyring_service
			) : ?>
				<div class="image-overlay">
					<header class="entry-header">
						<div class="image-overlay-content">
							<?php
							if ( 'instagram' != $keyring_service )
								echo '<h1 class="entry-title"><a href="' . esc_url( get_permalink() ) . '">' . get_the_title() . '</a></h1>';
							if ( trim( strip_tags( $remaining_content ) ) )
								echo force_balance_tags( $remaining_content );
							?>
						</div>
					</header><!-- .entry-header -->
				</div>
			<?php endif; ?>
		</div>

		<footer class="entry-meta">
			<?php homeroom_tags_list(); ?>
			<?php
			$icon = $service = false;
			if ( 'instagram' == get_post_meta( get_the_ID(), 'keyring_service', true ) ) {
				$icon = 'icon-instagram';
				$url = get_post_meta( get_the_ID(), 'instagram_url', true );
				$url = $url ? $url : 'http://instagram.com';
				$service = '<a href="' . esc_url( $url ) . '" rel="nofollow">Instagram</a>';
			} else if ( 'flickr' == get_post_meta( get_the_ID(), 'keyring_service', true ) ) {
				$icon = 'icon-flickr';
				$url = get_post_meta( get_the_ID(), 'flickr_url', true );
				$url = $url ? $url : 'http://flickr.com';
				$service = '<a href="' . esc_url( $url ) . '" rel="nofollow">Flickr</a>';
			}
			if ( $icon && $service ) {
				echo '<span class="post-source ' . esc_attr( $icon ) . '">' . sprintf( esc_html( __( 'Posted on %s', 'homeroom' ) ), $service ) . '</span>';
			}
			?>
			<?php homeroom_permalink_datestamp( false, 'icon-calendar permalink' ); ?>
			<?php edit_post_link( __( 'Edit', 'homeroom' ), '<span class="edit-link">', '</span>' ); ?>
			<?php get_template_part( 'map', 'singlepoint' ); ?>
		</footer><!-- .entry-meta -->

		<div class="clearfix"></div>
	</div><!-- .entry-content -->

</div>