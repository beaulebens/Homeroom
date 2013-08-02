<?php

// For trips; uses lines between markers (no windows)

if (
	1 != get_post_meta( get_the_ID(), 'geo_public', true )
||
	!$data = get_post_meta( get_the_ID(), 'geo_polyline', true )
) {
	return; // bail
}

$map_id = 'm' . md5( $data ); // Get something unique, must start with a char
$markers = json_decode( $data );
?>
<div id="map_<?php echo esc_js( $map_id ); ?>" class="map"></div>
<script type="text/javascript">
jQuery(document).one( 'post-load', function(e){
	var map_<?php echo esc_js( $map_id ); ?> = {
		positions : [
<?php foreach ( $markers as $m => $marker ) : $marker = explode( ',', $marker ); ?>
			new google.maps.LatLng( '<?php echo esc_js( $marker[0] ); ?>', '<?php echo esc_js( $marker[1] ); ?>' )<?php if ( $m < count( $markers ) - 1 ) { ?>,<?php } // that's a comma there, thanks, IE ?>
<?php endforeach; ?>
		],
<?php /* ?>
		descriptions : {
<?php foreach ( $markers as $m => $marker ) : ?>
<?php
$content = apply_filters( 'the_content', $marker->post_content );
$content .= '<p class="entry-meta">' . homeroom_permalink_datestamp( get_permalink( $marker->ID, 'icon-calendar permalink' ), false, $marker->post_date, false ) . '</p>';
?>
			<?php echo esc_js( $marker->ID ); ?> : '<?php echo rawurlencode( $content ); ?>'<?php if ( $m < count( $markers ) ) { ?>,
<?php } // thanks, IE ?>
<?php endforeach; ?>
	},
<?php */ ?>
		bounds : new google.maps.LatLngBounds(), // empty for now, we'll dynamically extend it later
		map : new google.maps.Map(
			document.getElementById( 'map_<?php echo esc_js( $map_id ); ?>' ),
			{
				mapTypeId: google.maps.MapTypeId.ROADMAP,
				center: new google.maps.LatLng( 0, 0 ),
				zoom: 0
			}
		),
		markers : {},
		line : false
	}; // end of map

	// Extend the bounds of interest based on our positions
	for ( var m = 0; m < map_<?php echo esc_js( $map_id ); ?>.positions.length; m++ ) {
		map_<?php echo esc_js( $map_id ); ?>.bounds.extend( map_<?php echo esc_js( $map_id ); ?>.positions[m] );
	}

	// Render all markers on our map
	for ( m = 0; m < map_<?php echo esc_js( $map_id ); ?>.positions.length; m++ ) {
		// Actual marker
		map_<?php echo esc_js( $map_id ); ?>.markers[m] = new google.maps.Marker( {
			clickable: false,
			map : map_<?php echo esc_js( $map_id ); ?>.map,
			position : map_<?php echo esc_js( $map_id ); ?>.positions[m]
		} );
	}

	// Add our flight path(s)
	map_<?php echo esc_js( $map_id ); ?>.line = new google.maps.Polyline({
		clickable : false,
		geodesic: true, // sweet Earth-curves
		map : map_<?php echo esc_js( $map_id ); ?>.map,
		path : map_<?php echo esc_js( $map_id ); ?>.positions,
		strokeColor : '#00f',
		strokeOpacity : 0.5,
		strokeWeight : 5
	});

	// Redraw map to fit our new marker-based bounds
	map_<?php echo esc_js( $map_id ); ?>.map.fitBounds( map_<?php echo esc_js( $map_id ); ?>.bounds );
});
</script>