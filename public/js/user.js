var user = function(){

	var class_alertSuccess = "alert-success";
	var class_alertDanger = "alert-danger";
	var validation_email = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    
    var register = function(){

		$( "#form_register" ).submit( function( event ){

			event.preventDefault();

			var button_process = $( "#form_register" ).find( "#action_process" );
			var message_error = $( "#form_register" ).find( ".message-error" );
			var txt_button = button_process.html();
			button_process.prop( "disabled", true ).html( '<i class="fa fa-spinner fa-spin fa-1x fa-fw"></i> Registrando' );
			$( ".form-control" ).removeClass( 'form-error' );
			message_error.html( '' ).removeClass( class_alertDanger ).addClass( 'hidden' );

			var confirm_pw = $( "#password_confirm" );
			var email = $( "#form_register" ).find( "#email" );
			var password = $( "#form_register" ).find( "#password" );
			var msg_text = "";
			var validation_send = true;

			if( email.val().length == 0 || email.val() == "" ){

				msg_text = msg_text + "El email es obligatorio<br>";
				email.addClass( 'form-error' );
				validation_send = false;
			}
			else{
				
				if( !validation_email.test( email.val() ) ){

					msg_text = msg_text + "El email no está en el formato permitido<br>";
					email.addClass( 'form-error' );
					validation_send = false;
				}
			}
			if( password.val().length == 0 || password.val() == "" ){

				msg_text = msg_text + "La contraseña es obligatoria<br>";
				password.addClass( 'form-error' );
				validation_send = false;
			}
			if( confirm_pw.val().length == 0 || confirm_pw.val() == "" ){

				msg_text = msg_text + "La confirmación de contraseña es obligatoria<br>";
				confirm_pw.addClass( 'form-error' );
				validation_send = false;
			}
			if( password.val() != confirm_pw.val() ){

				msg_text = msg_text + "Las contraseñas son diferentes<br>";
				$( "#password, #confirm_pw" ).addClass( 'form-error' );
				validation_send = false;
			}

			if( validation_send ){

				$.ajax({
					url: $( "#form_register" ).attr( "action" ),
					type: 'POST',
					dataType: 'json',
					cache: false,
					data: $( "#form_register" ).serialize(),
					headers: {
	                    'X-CSRF-TOKEN': $( 'meta[name="csrf-token"]' ).attr( 'content' )
	                }

				})
				.done(function( result ){

					if( result.result == 1 ){

						$( "#form_register" )[0].reset();
						
						msg_text = result.message;
						text_alert = "label-success";

						window.location.href="/App/panel";
					}
					else{

						$.each( result.message, function( index, value ){

							msg_text = msg_text + value + "<br>";
							text_alert = "label-danger";
						});

						grecaptcha.reset();
						message_error.html( msg_text ).removeClass( 'hidden' ).addClass( class_alertDanger );
						button_process.prop( "disabled", false ).html( txt_button );
					}
				})
				.fail(function( error ){

					grecaptcha.reset();
					console.warn( error );
					button_process.prop( "disabled", false ).html( txt_button );
				});
			}
			else{

				message_error.html( msg_text ).removeClass( 'hidden' ).addClass( class_alertDanger );
				button_process.prop( "disabled", false ).html( txt_button );
			}
		});
	}

	var validate_email = function(){

		$( "#email" ).change( function(){

			var email = $( this );
			if( !validation_email.test( email.val() ) ){

				$( "#help-email" ).html( 'El email no está en el formato permitido' ).removeClass( "hidden" ).addClass( 'label-danger' );
				$( "#action_process" ).prop( "disabled", true );
			}
			else{

				$( "#help-email" ).html( '' ).addClass( "hidden" ).removeClass( 'label-danger' );
				$( "#action_process" ).prop( "disabled", false );	
			}
		});

		$( "#email_login" ).change( function(){

			var email = $( this );
			if( !validation_email.test( email.val() ) ){

				$( "#help-email_login" ).html( 'El email no está en el formato permitido' ).removeClass( "hidden" ).addClass( 'label-danger' );
				$( "#action_process_login" ).prop( "disabled", true );
			}
			else{

				$( "#help-email_login" ).html( '' ).addClass( "hidden" ).removeClass( 'label-danger' );
				$( "#action_process_login" ).prop( "disabled", false );	
			}
		});

		$( "#email_recovery" ).change( function(){

			var email = $( this );
			if( !validation_email.test( email.val() ) ){

				$( "#help-email_recovery" ).html( 'El email no está en el formato permitido' ).removeClass( "hidden" ).addClass( 'label-danger' );
				$( "#action_process_recovery" ).prop( "disabled", true );
			}
			else{

				$( "#help-email_recovery" ).html( '' ).addClass( "hidden" ).removeClass( 'label-danger' );
				$( "#action_process_recovery" ).prop( "disabled", false );	
			}
		});
	}

	return{

		change_password: function(){

			change_password();
			confirm_password();
		},
		init: function(){

			$( ".g-recaptcha" ).find( "iframe" ).addClass( 'embed-responsive-item' );
			$( "iframe" ).find( ".rc-anchor.rc-anchor-compact.rc-anchor-light" ).css( "width", "100%" );
			$( ".rc-anchor-content" ).css( "width", "100%" );
			$( ".rc-anchor-content" ).find( '.rc-inline-block' ).css( "width", "100%" );
			$( ".rc-anchor-compact-footer" ).css( "width", "100%" );

			confirm_password();
			login();
			modal_user();
			recovery();
			register();
			validate_email();
		}
	}
}();