jQuery( document ).ready( function( $ ) {
	// Enable fixed menu on scroll
	var menu = $( '.main-navigation' ), pos = menu.offset(), abh = $( '#wpadminbar' ).height();
	$( window ).scroll( function(){
		if ( $( this ).scrollTop() + abh >= pos.top && $( 'body' ).hasClass( 'default-menu' ) ) {
			$( 'body' ).removeClass( 'default-menu' ).addClass( 'fixed-menu' );
		} else if ( $( this ).scrollTop() + abh < pos.top && $( 'body' ).hasClass( 'fixed-menu' ) ) {
			$( 'body' ).removeClass( 'fixed-menu' ).addClass( 'default-menu' );
		}
	});

	// Attach hover handler for image overlays
	// @todo fix horrible flash when you hover over the overlay
	if ( !$( 'body.single' ).length ) {
		$( '#content' ).on( 'mouseover', 'div.format-image .entry-content a img', function() {
			$( this ).parent().siblings( '.image-overlay' ).css( 'display', 'block' );
		} );
		$( '#content' ).on( 'mouseout', 'div.format-image .entry-content a img', function() {
			$( this ).parent().siblings( '.image-overlay' ).css( 'display', 'none' );
		} );
	}

	// Handle .collapse blocks (consecutive tweets)
	$( '.collapse' ).each( function( index, elem ) {
		kids = $( this ).children( 'div.post' ).length;
		$( this ).before( '<div class="collapse-msg"><a href="#">&hellip;and ' + kids + ' more on ' + $( this ).data( 'date' ) + '</a></div>' );
	} );
	$( '.collapse-msg a' ).each( function( index, elem ) {
		$( this ).click( function( e ) {
			e.preventDefault();
			$( this ).parent().slideUp().siblings( '.collapse' ).slideDown();
			return false;
		} );
	} );

	// Keyboard navigation for older/newer. Works on single posts and for listing paging
	$( document ).keydown( function( e ) {
		var url = false;
		if ( e.which == 37 ) {  // Left arrow key code
			url = $( '.nav-previous a' ).attr( 'href' );
		}
		else if ( e.which == 39 ) {  // Right arrow key code
			url = $( '.nav-next a' ).attr( 'href' );
		}
		if ( url && ( !$( 'textarea, input, select' ).is( ':focus' ) ) ) {
			window.location = url;
		}
	} );

	// Masonry for layout when available
	var container = jQuery( '#masonry' );
	if ( container.length ) {
		setTimeout( function() {
			container.imagesLoaded( function() {
				container.masonry( {
					itemSelector : 'article',
					columnWidth : function( containerWidth ) {
						return containerWidth / 4;
					}
				} );
			} );
		}, 100 );
		for ( i = 1; i < 40; i++ ) {
			setTimeout( function() { container.masonry( 'reload' ); }, i * 500 );
		}

		// Re-arrange on Infinite Scroll
		jQuery( document ).on( 'post-load', function() {
			var infinite_container = jQuery( '.infinite-wrap' );
			for ( i = 1; i < 40; i++ ) {
				setTimeout( function() {
					infinite_container.masonry( {
						itemSelector : 'article',
						columnWidth : function( containerWidth ) {
							return containerWidth / 4;
						}
					} );
				}, i * 500 );
			}
		} );
	}

	// Relocate Jetpack sharing buttons down into the comments form
	jQuery( '#sharing' ).html( jQuery( '.sharedaddy' ).detach() );
} );