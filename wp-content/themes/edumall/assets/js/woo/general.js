jQuery( document ).ready( function( $ ) {
	'use strict';

	var $body = $( 'body' );

	initQuickViewPopup();

	function initQuickViewPopup() {
		$( '.edumall-product' ).on( 'click', '.quick-view-btn', function( e ) {
			e.preventDefault();
			e.stopPropagation();

			var $button = $( this );

			var $actions = $button.parents( '.product-actions' ).first();
			$actions.addClass( 'refresh' );

			$button.addClass( 'loading' );
			var productID = $button.data( 'pid' );

			/**
			 * Avoid duplicate ajax request.
			 */
			var $popup = $body.children( '#' + 'popup-product-quick-view-content-' + productID );
			if ( $popup.length > 0 ) {
				openQuickViewPopup( $popup, $button );
			} else {
				var data = {
					action: 'product_quick_view',
					pid: productID
				};

				$.ajax( {
					url: $edumall.ajaxurl,
					type: 'POST',
					data: $.param( data ),
					dataType: 'json',
					success: function( results ) {
						$popup = $( results.template );
						$body.append( $popup );
						openQuickViewPopup( $popup, $button );
					},
				} );
			}
		} );
	}

	function openQuickViewPopup( $popup, $button ) {
		$button.removeClass( 'loading' );

		$.magnificPopup.open( {
			mainClass: 'mfp-fade popup-product-quick-view',
			items: {
				src: $popup.html(),
				type: 'inline'
			},
			callbacks: {
				open: function() {
					var $sliderWrap = this.content.find( '.woo-single-gallery' );
					var thumbsSlider = $sliderWrap.children( '.edumall-thumbs-swiper' ).EdumallSwiper();
					var mainSlider = $sliderWrap.children( '.edumall-main-swiper' ).EdumallSwiper( {
						thumbs: {
							swiper: thumbsSlider
						}
					} );

					this.content.find( '.entry-summary .inner-content' ).perfectScrollbar( {
						suppressScrollX: true
					} );
				},
			}
		} );
	}
} );
