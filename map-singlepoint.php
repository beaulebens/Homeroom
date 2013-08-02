<?php

if (
	is_singular()
&&
	0 !== get_post_meta( get_the_ID(), 'geo_public', true )
&&
	get_post_meta( get_the_ID(), 'geo_latitude', true )
&&
	get_post_meta( get_the_ID(), 'geo_longitude', true )
) {
	echo '<div class="map-singlepoint' . ( is_front_page() ? '' : ' icon-location' ) . '">';
	homeroom_render_map(
		get_post_meta( get_the_ID(), 'geo_latitude', true ),
		get_post_meta( get_the_ID(), 'geo_longitude', true )
	);
	echo '</div>';
}
