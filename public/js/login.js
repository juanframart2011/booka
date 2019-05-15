var login = function(){

	var class_alertSuccess = "alert-success";
	var class_alertDanger = "alert-danger";
	var validation_email = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

	var change_password = function(){

		$( "#form_change_password" ).submit( function( event ){

			event.preventDefault();
			
			var button_process = $( "#form_change_password" ).find( "#action_process" );
			var message_error = $( "#form_change_password" ).find( ".message-error" );
			var confirm_pw = $( "#form_change_password" ).find( "#password_confirm" );
			var password = $( "#form_change_password" ).find( "#password" );

			button_process.prop( "disabled", true );
			var confirm_pw = $( "#form_change_password" ).find( "#password_confirm" );
			$( "#form_change_password" ).find( ".form-control" ).removeClass( 'form-error' );
			message_error.html( '' ).removeClass( class_alertDanger ).addClass( 'hidden' );

			var txt_button = button_process.html();
			button_process.prop( "disabled", true ).html( '<i class="fa fa-spinner fa-spin fa-1x fa-fw"></i> Actualizando' );

			var msg_text = "";
			var validation_send = true;

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
				$( "#form_change_password" ).find( "#password, #confirm_pw" ).addClass( 'form-error' );
				validation_send = false;
			}

			if( validation_send ){

				$.ajax({
					url: $( "#form_change_password" ).attr( "action" ),
					type: 'POST',
					dataType: 'json',
					data: $( "#form_change_password" ).serialize(),
					headers: {
	                    'X-CSRF-TOKEN': $( 'meta[name="csrf-token"]' ).attr( 'content' )
	                }
				})
				.done(function( result ){

					if( result.result == 1 ){

						$( "#form_change_password" )[0].reset();
						message_error.html( result.message ).removeClass( 'hidden' ).addClass( class_alertSuccess );
						button_process.prop( "disabled", false ).html( txt_button );

						setTimeout( function(){

							window.location.href=url_public + '/login';
						}, 1700 );
					}
					else{

						$.each( result.message, function( index, value ){

							msg_text = msg_text + value + "<br>";
						});

						message_error.html( msg_text ).removeClass( 'hidden' ).addClass( class_alertDanger );
						button_process.prop( "disabled", false ).html( txt_button );
					}
				})
				.fail(function( error ){

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

	var recovery = function(){

		$( "#form_recovery" ).submit( function( event ){

			event.preventDefault();

			var button_process = $( "#action_process_recovery" );
			var message_error = $( "#form_recovery" ).find( ".message-error" );
			var txt_button = button_process.html();

			var txt_button = button_process.html();
			button_process.prop( "disabled", true ).html( '<i class="fa fa-spinner fa-spin fa-1x fa-fw"></i> Recuperando' );

			$( ".form-control" ).removeClass( 'form-error' );
			message_error.html( '' ).removeClass( class_alertDanger ).addClass( 'hidden' );

			var email = $( "#email_recovery" );
			var msg_text = "";
			var validation_send = true;

			if( email.val().length == 0 || email.val() == "" ){

				msg_text = "El email es obligatorio<br>";
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

			if( validation_send ){

				$.ajax({
					url: $( "#form_recovery" ).attr( "action" ),
					type: 'POST',
					dataType: 'json',
					data: {
						email_recovery: email.val()
					},
					headers: {
	                    'X-CSRF-TOKEN': $( 'meta[name="csrf-token"]' ).attr( 'content' )
	                }
				})
				.done(function( result ){

					if( result.result == 1 ){

						$( "#form_recovery" )[0].reset();
						message_error.html( result.message ).removeClass( 'hidden' ).addClass( class_alertSuccess );
						button_process.prop( "disabled", false ).html( txt_button );
					}
					else{

						$.each( result.message, function( index, value ){

							msg_text = msg_text + value + "<br>";
						});

						message_error.html( msg_text ).removeClass( 'hidden' ).addClass( class_alertDanger );
						button_process.prop( "disabled", false ).html( txt_button );
					}
				})
				.fail(function( error ){

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

	return{

		change_password: function(){

			change_password();
		},
		init: function(){

			recovery();
		}
	}
}();