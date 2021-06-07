jQuery( document ).ready( function( $ ) {
	'use strict';

	var $body = $( 'body' );

	/**
	 * Edition version of wishlist function.
	 */
	$( document ).on( 'click', '.edumall-course-wishlist-btn', function( e ) {
		e.preventDefault();

		if ( $( 'body' ).hasClass( 'logged-in' ) ) {
			var $that = $( this );
			var courseId = $that.attr( 'data-course-id' );

			$.ajax( {
				url: $edumall.ajaxurl,
				type: 'POST',
				data: {
					course_id: courseId,
					'action': 'tutor_course_add_to_wishlist'
				},
				beforeSend: function() {
					$that.addClass( 'updating-icon' );
				},
				success: function( data ) {
					if ( data.success ) {
						if ( data.data.status === 'added' ) {
							$that.addClass( 'has-wish-listed' );

							$that.find( '.button-text' ).text( $edumallCourseWishlist.addedText );
						} else {
							$that.removeClass( 'has-wish-listed' );

							$that.find( '.button-text' ).text( $edumallCourseWishlist.addText );
						}
					} else {
						window.location = data.data.redirect_to;
					}
				},
				complete: function() {
					$that.removeClass( 'updating-icon' );
				}
			} );
		} else {
			$().EdumallPopupLogin( 'login' );
		}
	} );

	/**
	 * Edition version of edit review form on dashboard.
	 */
	$( document ).on( 'submit', '#custom_tutor_update_review_form', function( e ) {
		e.preventDefault();

		var $that = $( this );
		var review_id = $that.closest( '.tutor-edit-review-modal-wrap ' ).attr( 'data-review-id' );

		var nonce_key = _tutorobject.nonce_key;

		var rating = $that.find( 'input[name="tutor_rating_gen_input"]' ).val();
		var review = $that.find( 'textarea[name="review"]' ).val();
		review = review.trim();

		var json_data = {
			review_id: review_id,
			rating: rating,
			review: review,
			action: 'edumall_update_review_modal' // Custom ajax action
		};
		json_data[ nonce_key ] = _tutorobject[ nonce_key ];

		$.ajax( {
			url: _tutorobject.ajaxurl,
			type: 'POST',
			data: json_data,
			beforeSend: function() {
				$that.find( 'button[type="submit"]' ).addClass( 'tutor-updating-message' );
			},
			success: function( data ) {
				if ( data.success ) {
					// Close the modal.
					$( '.tutor-edit-review-modal-wrap' ).removeClass( 'show' );
					location.reload( true );
				}
			},
			complete: function() {
				$that.find( 'button[type="submit"]' ).removeClass( 'tutor-updating-message' );
			}
		} );
	} );

	/**
	 * Edition version of edit review form in single.
	 */
	$( document ).on( 'click', '.custom_tutor_submit_review_btn', function( e ) {
		e.preventDefault();
		var $that = $( this );
		var rating = $that.closest( 'form' ).find( 'input[name="tutor_rating_gen_input"]' ).val();
		var review = $that.closest( 'form' ).find( 'textarea[name="review"]' ).val();
		review = review.trim();

		var course_id = $( 'input[name="tutor_course_id"]' ).val();
		var data = {
			course_id: course_id,
			rating: rating,
			review: review,
			action: 'edumall_place_rating'
		};

		if ( review ) {
			$.ajax( {
				url: _tutorobject.ajaxurl,
				type: 'POST',
				data: data,
				beforeSend: function() {
					$that.addClass( 'updating-icon' );
				},
				success: function( data ) {
					var review_id = data.data.review_id;
					var review = data.data.review;
					$( '.tutor-review-' + review_id + ' .review-content' ).html( review );
					location.reload();
				}
			} );
		}
	} );

	$( '.btn-toggle-lesson-sidebar' ).on( 'click', function() {
		$body.toggleClass( 'lesson-sidebar-on' );
	} );

	$( document ).on( 'click', '.tutor-single-lesson-a', function() {
		$body.removeClass( 'lesson-sidebar-on' );
	} );

	$( document ).on( 'click', function( evt ) {
		if ( evt.target.id === 'tutor-lesson-sidebar' ) {
			return;
		}

		if ( $( evt.target ).closest( '#tutor-lesson-sidebar' ).length ) {
			return;
		}

		if ( $( evt.target ).closest( '.btn-toggle-lesson-sidebar' ).length ) {
			return;
		}

		if ( window.innerWidth >= 992 ) {
			return;
		}

		$body.removeClass( 'lesson-sidebar-on' );
	} );

	var withdrawMethodInput = $( '.withdraw-method-select-input' );

	withdrawMethodInput.on( 'change', function( e ) {
		$( '.withdraw-account-save-btn-wrap' ).show();
	} );

	withdrawMethodInput.each( function() {
		var $that = $( this );
		if ( $that.is( ":checked" ) ) {
			$( '.withdraw-account-save-btn-wrap' ).show();
		}
	} );

	$( '.dashboard-header-toggle-menu' ).on( 'click', function() {
		$( '.tutor-dashboard-left-menu' ).toggleClass( 'opened' );
	} );

	handleDashboardMenuBar();

	$( window ).on( 'resize', function() {
		handleDashboardMenuBar();
	} );

	function handleDashboardMenuBar() {
		var wWidth = window.innerWidth;

		if ( wWidth >= 1200 ) {
			$body.addClass( 'dashboard-nav-fixed' );
		} else {
			$body.removeClass( 'dashboard-nav-fixed' );
		}

		var wHeight = window.innerHeight;
		var navHeaderHeight = $( '.dashboard-nav-header' ).outerHeight();
		var bottomSpacing = 50;
		var navContentHeight = wHeight - navHeaderHeight - bottomSpacing;

		$( '.dashboard-nav-content-inner' ).height( navContentHeight ).perfectScrollbar( { suppressScrollX: true } );
	}
} );
