<?php
if ( post_password_required() ) {
	echo get_the_password_form();
	return;
}
?>

<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php
	$keyring_service = wp_get_object_terms( get_the_ID(), 'keyring_services' );
	if ( $keyring_service )
		$keyring_service = $keyring_service[0]->name;

	if ( get_post_meta( get_the_ID(), 'geo_polyline', true ) ) {
		get_template_part( 'snippet', 'status-tripit' );
	} else if ( 'Moves' == $keyring_service ) {
		get_template_part( 'snippet', 'status-moves' );
	} else {
		$GLOBALS['homeroom_map_markers'] = array( $post );
		get_template_part( 'map', 'multipoint' );

		echo '<div class="entry-content">';
		the_content();
		echo '</div>';
	}

	?>
	<footer class="entry-meta">
		<?php homeroom_tags_list(); ?>
		<?php
		$icon = $service = false;
		if ( 'Foursquare' == $keyring_service ) {
			$icon = 'icon-foursquare';

			// If they've filled out their profile, we can build a full URL, otherwise just link to Foursquare
			if ( $username = get_user_meta( $post->post_author, 'foursquare', true ) )
				$service = '<a href="http://foursquare.com/' . esc_attr( $username ) . '/checkin/' . esc_attr( get_post_meta( get_the_ID(), 'foursquare_id', true ) ) . '" rel="nofollow">Foursquare</a>';
			else
				$service = '<a href="http://foursquare.com" rel="nofollow">Foursquare</a>';
		} else if ( 'TripIt' == $keyring_service ) {
			$icon    = 'icon-location';
			$service = '<a href="http://tripit.com">TripIt</a>';
		}
		if ( $icon && $service ) {
			echo '<span class="post-source ' . esc_attr( $icon ) . '">' . sprintf( esc_html( __( 'Originally on %s', 'homeroom' ) ), $service ) . '</span>';
		}
		?>
		<?php homeroom_permalink_datestamp( false, 'icon-link permalink' ); ?>
		<?php edit_post_link( __( 'Edit', 'homeroom' ), '<span class="edit-link">', '</span>' ); ?>
		<?php get_template_part( 'map', 'singlepoint' ); ?>
	</footer><!-- .entry-meta -->

</div>