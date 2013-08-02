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
		$( '#content' ).on( 'hover', 'div.format-image .entry-content .image-grouping', function() {
			// $( this ).find( '.image-overlay' ).slideToggle( 'fast' );
			$( this ).find( '.image-overlay' ).animate( {
				bottom: '-500px'
			} );
		}, function() {
			$( this ).find( '.image-overlay' ).animate( {
				bottom: '0px'
			} );
		} );
	}

	// Handle .collapse blocks (consecutive tweets/links)
	$( document ).on( 'post-load', function() {
		$( '.collapse' ).each( function( index, elem ) {
			if ( $( this ).siblings( '.collapse-msg' ).length )
				return;
			child_count = $( this ).children( 'div.post' ).length;
			$( this ).before( '<div class="collapse-msg"><a href="#">&hellip;and ' + child_count + ' more on ' + $( this ).data( 'date' ) + '</a></div>' );
		} );
		$( '.collapse-msg a' ).each( function( index, elem ) {
			$( this ).click( function( e ) {
				e.preventDefault();
				$( this ).parent().slideUp().siblings( '.collapse' ).slideDown();
				return false;
			} );
		} );
	} );

	// Keyboard navigation for older/newer. Works on single posts and for listing pages. Don't enable
	// when Carousel is defined, because they clash.
	if ( 'undefined' == typeof jetpackCarouselStrings ) {
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
	}

	// Masonry for layout when available
	var container = $( '#masonry' );
	if ( container.length ) {
		setTimeout( function() {
			container.imagesLoaded( function() {
				container.masonry( {
					itemSelector : 'article',
					isFitWidth: true,
					columnWidth : function( containerWidth ) {
						return containerWidth / 3;
					}
				} );
			} );
		}, 100 );
		for ( i = 1; i < 40; i++ ) {
			setTimeout( function() { container.masonry( 'reload' ); }, i * 500 );
		}

		// Re-arrange on Infinite Scroll
		$( document ).on( 'post-load', function() {
			var infinite_container = $( '.infinite-wrap' );
			for ( i = 1; i < 40; i++ ) {
				setTimeout( function() {
					infinite_container.masonry( {
						itemSelector : 'article',
						isFitWidth: true,
						columnWidth : function( containerWidth ) {
							return containerWidth / 3;
						}
					} );
				}, i * 500 );
			}
		} );
	}

	// Render Tweets that are loaded via Infinite Scroll
	$( document ).on( 'post-load', function() {
		if ( 'undefined' != typeof twttr && 'undefined' != typeof twttr.widgets )
			twttr.widgets.load();
	} );

	// Relocate Jetpack sharing buttons down into the comments form
	$( '#sharing' ).html( $( '.sharedaddy' ).detach() );

	// If we find Infinite Scroll (Jetpack), then remove the bottom navigation, since we'll just scroll
	if ( 'undefined' !== typeof infiniteScroll ) {
		$( '#nav-below' ).slideUp( function() { $( this ).remove(); } );
	}

	// Fire off the same event as Infinite Scroll, since everything is hooked to that
	$( document ).trigger( 'post-load' );
} );