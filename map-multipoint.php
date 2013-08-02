<?php
global $homeroom;

// For multiple, disconnected markers; includes InfoWindows
// If a single page, then auto-open the first InfoWindow

if ( !$homeroom->map_markers || !count( $homeroom->map_markers ) )
	return; // move along, nothing to see here

$markers = $homeroom->map_markers;
$map_id = 'm' . md5( json_encode( $markers ) ); // Get something unique, must start with a char

?>
<div id="gmap_<?php echo esc_js( $map_id ); ?>" class="map"></div>
<script type="text/javascript">
jQuery(document).one( 'post-load', function(e){
	var gmap_<?php echo esc_js( $map_id ); ?> = {
		positions : {
<?php foreach ( $markers as $m => $marker ) : ?>
			<?php echo esc_js( $marker->ID ); ?> : new google.maps.LatLng( '<?php echo get_post_meta( $marker->ID, 'geo_latitude', true ); ?>', '<?php echo get_post_meta( $marker->ID, 'geo_longitude', true ); ?>' )<?php if ( $m < count( $markers ) - 1 ) { ?>,<?php } // that's a comma there, thanks, IE ?>
<?php endforeach; ?>
		},
		descriptions : {
<?php foreach ( $markers as $m => $marker ) : ?>
<?php
$content = '<div class="marker-info">' . apply_filters( 'the_content', $marker->post_content ) . '</div>';
$content .= '<p class="entry-meta">' . homeroom_permalink_datestamp( get_permalink( $marker->ID ), 'icon-calendar permalink', $marker->post_date, false ) . '</p>';
?>
			<?php echo esc_js( $marker->ID ); ?> : '<?php echo rawurlencode( $content ); ?>'<?php if ( $m < count( $markers ) ) { ?>,
<?php } ?>
<?php endforeach; ?>
	},
		bounds : new google.maps.LatLngBounds(), // empty for now, we'll dynamically extend it later
		map : new google.maps.Map(
			document.getElementById( 'gmap_<?php echo esc_js( $map_id ); ?>' ),
			{
				mapTypeId: google.maps.MapTypeId.ROADMAP,
				center: new google.maps.LatLng( 0, 0 ),
				zoom: 0
			}
		),
		markers : {},
		windows : {},
		closeAllWindows : function() {
			for ( var n in gmap_<?php echo esc_js( $map_id ); ?>.windows )
				gmap_<?php echo esc_js( $map_id ); ?>.windows[n].close();
		},
		addWindow : function( m ) {
			google.maps.event.addListener(
				gmap_<?php echo esc_js( $map_id ); ?>.markers[m],
				'click',
				function() {
					// Close any open ones
					gmap_<?php echo esc_js( $map_id ); ?>.closeAllWindows();

					// Open the one we clicked on
					gmap_<?php echo esc_js( $map_id ); ?>.windows[m].open( gmap_<?php echo esc_js( $map_id ); ?>.map, gmap_<?php echo esc_js( $map_id ); ?>.markers[m] );

					// Pan to center
					gmap_<?php echo esc_js( $map_id ); ?>.map.panTo( gmap_<?php echo esc_js( $map_id ); ?>.positions[m] );
				}
			)
		}
	}; // end of gmap

	// Extend the bounds of interest based on our positions
	for ( var m in gmap_<?php echo esc_js( $map_id ); ?>.positions ) {
		gmap_<?php echo esc_js( $map_id ); ?>.bounds.extend( gmap_<?php echo esc_js( $map_id ); ?>.positions[m] );
	}

	// Render Markers + Windows
	for ( var m in gmap_<?php echo esc_js( $map_id ); ?>.positions ) {
		// Marker
		gmap_<?php echo esc_js( $map_id ); ?>.markers[m] = new google.maps.Marker( {
			clickable: true,
			map : gmap_<?php echo esc_js( $map_id ); ?>.map,
			position : gmap_<?php echo esc_js( $map_id ); ?>.positions[m]
		} );
		// InfoWindow
		gmap_<?php echo esc_js( $map_id ); ?>.windows[m] = new google.maps.InfoWindow( {
			content : decodeURIComponent( gmap_<?php echo esc_js( $map_id ); ?>.descriptions[m] ),
			position : gmap_<?php echo esc_js( $map_id ); ?>.positions[m],
			pixelOffset : new google.maps.Size( 0, 0 ),
			maxWidth : 300
		} );
		gmap_<?php echo esc_js( $map_id ); ?>.addWindow( m );
	}

	// Redraw map to fit our new marker-based bounds, or a default zoom that works better for single markers
	<?php if ( count( $markers ) > 1 ) : ?>
	gmap_<?php echo esc_js( $map_id ); ?>.map.fitBounds( gmap_<?php echo esc_js( $map_id ); ?>.bounds );
	<?php else : ?>
	gmap_<?php echo esc_js( $map_id ); ?>.map.setCenter( gmap_<?php echo esc_js( $map_id ); ?>.markers[<?php echo esc_js( $markers[0]->ID ); ?>].position );
	gmap_<?php echo esc_js( $map_id ); ?>.map.setZoom( 15 );
	<?php endif; ?>

	// Click handler to close infowindows when you click somewhere else on the map
	google.maps.event.addListener( gmap_<?php echo esc_js( $map_id ); ?>.map, 'click', gmap_<?php echo esc_js( $map_id ); ?>.closeAllWindows );

	<?php if ( is_singular() ) :  ?>
		// Auto-open the first InfoWindow
		for ( var m in gmap_<?php echo esc_js( $map_id ); ?>.windows ) {
			gmap_<?php echo esc_js( $map_id ); ?>.windows[m].open( gmap_<?php echo esc_js( $map_id ); ?>.map );
			break;
		}
	<?php endif; ?>
});
</script>
<?php
global $homeroom_just_did_map;
$homeroom_just_did_map = true;
?>
