(
	function( $ ) {
		'use strict';

		var EdumallRegisterFormHandler = function( $scope, $ ) {
			var $registerForm = $scope.find( 'form' );

			$registerForm.validate( {
				rules: {
					username: {
						required: true,
						minlength: 4,
					},
					email: {
						required: true,
						email: true
					},
					password: {
						required: true,
						minlength: 8,
						maxlength: 30
					},
				},
				submitHandler: function( form ) {
					var $form = $( form );

					$.ajax( {
						url: $edumall.ajaxurl,
						type: 'POST',
						cache: false,
						dataType: 'json',
						data: $form.serialize(),
						success: function( response ) {
							if ( response.success !== true ) {
								$form.find( '.form-response-messages' ).text( response.messages ).addClass( 'error' ).show();
							} else {
								$form.find( '.form-response-messages' ).text( response.messages ).addClass( 'success' ).show();
								location.reload();
							}
						},
						beforeSend: function() {
							$form.find( 'button[type="submit"]' ).addClass( 'updating-icon' );
						},
						complete: function() {
							$form.find( 'button[type="submit"]' ).removeClass( 'updating-icon' );
						}
					} );
				}
			} );

			var messages = $edumallLogin.validatorMessages;

			jQuery.extend( jQuery.validator.messages, {
				required: messages.required,
				remote: messages.remote,
				email: messages.email,
				url: messages.url,
				date: messages.date,
				dateISO: messages.dateISO,
				number: messages.number,
				digits: messages.digits,
				creditcard: messages.creditcard,
				equalTo: messages.equalTo,
				accept: messages.accept,
				maxlength: jQuery.validator.format( messages.maxlength ),
				minlength: jQuery.validator.format( messages.minlength ),
				rangelength: jQuery.validator.format( messages.rangelength ),
				range: jQuery.validator.format( messages.range ),
				max: jQuery.validator.format( messages.max ),
				min: jQuery.validator.format( messages.min )
			} );
		};

		$( window ).on( 'elementor/frontend/init', function() {
			elementorFrontend.hooks.addAction( 'frontend/element_ready/tm-register-form.default', EdumallRegisterFormHandler );
		} );
	}
)( jQuery );
