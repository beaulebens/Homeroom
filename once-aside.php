<?php

// Check if the author of this post is connected to the Twitter importer,
// if so load their Twitter avatar, if not use their Gravatar

$icon_size = 35; // In px, how big (square) should the avatar/icon be?

// I'm being a horrible person and accessing options directly to avoid re-loading all the importer code
if ( class_exists( 'Keyring' ) ) {
	if ( $importer = get_option( 'keyring_twitter_importer' ) ) {
		if ( $token = $importer['token'] ) {
			$token = Keyring::get_token_store()->get_token( array( 'id' => $token, 'type' => 'access' ) );
			$url = $token->get_meta( 'picture' );
			if ( $url ) {
				// Use Photon (if available) to resize their icon properly
				if ( function_exists( 'jetpack_photon_url' ) ) {
					$url = jetpack_photon_url( $url, array(
						'resize' => "$icon_size,$icon_size",
						'filter' => 'grayscale', // Boom!
					) );
				}
				// Output that sucka
				echo '<a href="' . get_author_posts_url( false, get_the_author_meta( 'ID' ) ) . '">';
				echo '<img src="' . esc_url( $url ) . '" class="format-icon" width="' . esc_attr( $icon_size ) . '" height="' . esc_attr( $icon_size ) . '" alt="" />';
				echo '</a>';
				return; // Avoid doing the Gravatar, below
			}
		}
	}
}

// No Twitter connection, try to use their Gravatar instead
$gravatar = get_avatar( get_the_author_ID(), $icon_size );
$gravatar = str_replace( "class='", "class='format-icon ", $gravatar );
echo '<a href="' . get_author_posts_url( false, get_the_author_meta( 'ID' ) ) . '">';
echo $gravatar;
echo '</a>';