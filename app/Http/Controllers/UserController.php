<?php
namespace App\Http\Controllers;

use App\Mail\UserMail;

use App\Book_Lend;
use App\Rol;
use App\Status;
use App\User;
use App\User_Recovery;
use App\User_Session;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

use Validator;

class UserController extends Controller
{	
	#Función para mostrar vista de cambio de contraseña
	public function change_password( Request $request, $url ){

		if( empty( $url ) ){

			abort( 404 );
		}
		else{

			$result = User_Recovery::where([ "userRecovery_encrypted" => $url ])->get();
			
			if( count( $result ) == 0 ){

				abort( 404 );
			}
			else{

				$user = User::where( "user_id", $result[0]->user_id )->get();

				$data["user"] = $user;

				if( $result[0]->status_id != 1 ){

					$data["visibility_change"] = false;
				}
				else{

					$data["url"] = $url;
					$data["visibility_change"] = true;
				}

				return view( 'user.change_password', $data );
			}
		}
	}

	#Función para acción de cambio de contraseña
	public function change_password_action( Request $request ){

		if( $request->ajax() ){

			$message = 	[
							"key_pw.required" => "La url es obligatoria",
							"key_pw.exists" => "La url no existe",
							"password.required" => "La contraseña es obligatoria",
							"password_confirm.required" => "La confirmación de contraseña es obligatorio",
							"password_confirm.same" => "Las contraseñas son diferentes",
						];

			$validate = Validator::make($request->all(), [
				"key_pw" => "required|exists:user_recovery,userRecovery_encrypted",
				"password" => "required",
				"password_confirm" => "required|same:password",
			], $message );

			if( $validate->fails() ){

				$error = $validate->errors()->all();

				return response()->json([
	                "result" => 2,
	                "message" => $error
	            ], 200 );
			}
			else{

				$url = $request->get( "key_pw" );
				$password = md5( $request->get( "password" ) );

				$result = User_Recovery::where([ "userRecovery_encrypted" => $url, "status_id" => 1 ]);

				if( $result->count() == 0 ){

					return response()->json([
		                "result" => 2,
		                "message" => array( "Lo sentimos, la url ha caducado" )
		            ], 200 );
				}
				else{

					DB::beginTransaction();

					try{

						$recovery_update = $result;
						$result = $result->get();
						$user_id = $result[0]->user_id;
						$user_update = User::where( "user_id", $user_id );
						$user_update->update([
							"user_password" => $password,
							"user_lastModification" => date( "Y-m-d H:i:s" )
						]);

						$recovery_update->update([
							"status_id" => 2,
							"userRecovery_lastModification" => date( "Y-m-d H:i:s" )
						]);

						DB::commit();

						return response()->json([
			                "result" => 1,
			                "message" => "Se realizo con éxito el cambio de contraseña"
			            ], 200 );
					}
					catch( \Exception $e ){

						DB::rollback();

						Log::warning( "UserController.change_password_action() :: " . $e->getMessage() );

						return response()->json([
							"result" => 2,
							"message" => array( "Ocurrió un problema inesperado, revise que todos los campos tienen el formato requerido." )
						], 200 );
					}
				}
			}
		}
		else{

			abort( 404 );
		}
	}
    
    #Login
    public function home( Request $request ){

    	$user = $request->session()->get( "us3r-id" );

    	if( !empty( $user ) ){

    		return redirect( "App/home" );
    	}
    	else{

    		return view( "login" );
    	}
    }

    #Función para eliminado lógico de usuario
    public function delete( Request $request ){

    	$id = $request->get( "id" );
    	if( empty( $id ) ){

			abort( 404 );
		}
		else{

			$user = User::where([ 'user.user_encrypted' => $id ]);
    		
    		$user->update([ "status_id" => 2]);

    		return redirect( "App/home" );
		}
    }

    #Función que muestra detalle de usuario
    public function detail( Request $request ){

    	$id = $request->get( "id" );
    	if( empty( $id ) ){

			abort( 404 );
		}
		else{

			$data["site_title"] = "Registro de usuarios";
			$data["menu_user"] = true;
			$data["rols"] = Rol::where([ "status_id" => 1 ])->Orderby( "rol_name", "asc" )->get();
			$user = User::where([ 'user.user_encrypted' => $id ])
									->join( "rol", "user.rol_id", "=", "rol.rol_id" )
									->Orderby( "user_creationDate", "asc" )
									->get();
    		$data["user"] = $user[0];
    		$data["book_lend"] = Book_Lend::where([ "book_lend.status_id" => 1, "book_lend.user_id" => $user[0]->user_id ])
    							->join( "book", "book_lend.book_id", "=", "book.book_id" )
    							->get();

    		return view( "user.detail", $data );
		}
    }

    #Función para recuperar contraseña
	public function forgot( Request $request ){

		if( $request->ajax() ){

			$message = 	[
							"email_recovery.required" => "El email es obligatorio",
							"email_recovery.exists" => "El email no existe",
							"email_recovery.email" => "El email no está en el formato permitido"
						];

			$validate = Validator::make($request->all(), [
				"email_recovery" => "required|email|exists:user,user_email",
			], $message );

			if( $validate->fails() ){

				$error = $validate->errors()->all();

				return response()->json([
	                "result" => 2,
	                "message" => $error
	            ], 200 );
			}
			else{

				$user = $request->get( "email_recovery" );
				$result = User::where( "user_email", $user )->get();

				$result_recovery = User_Recovery::where([ "user_id" => $result[0]->user_id, "status_id" => 1 ])->count();

				if( $result_recovery > 0 ){

					return response()->json([
						"result" => 2,
						"message" => array( "Lo sentimos ya has requerido una recuperación de contraseña y se encuentra activa, revisa tu bandeja de entrada o dejanos un mensaje en nuestro correo de contacto." )
					], 200 );
				}
				else{

					DB::beginTransaction();

					try{

						$userRecovery_encrypted = md5( date( "YmdHis" ) * $result[0]->user_id );

						$date_current = date( "Y-m-d" );

						$date_expired = date( 'Y-m-d', strtotime( $date_current . ' +1 day') );
						
						$userRecovery_add = new User_Recovery;
						$userRecovery_add->user_id = $result[0]->user_id;
						$userRecovery_add->userRecovery_encrypted = $userRecovery_encrypted;
						$userRecovery_add->userRecovery_create = $date_current;
						$userRecovery_add->userRecovery_expired = $date_expired;
						$userRecovery_add->userRecovery_creationDate = date( "Y-m-d H:i:s" );
						$userRecovery_add->save();

						if( $userRecovery_add->id > 0 ){

							$data["user"] = $result[0];
							$data["url"] = $userRecovery_encrypted;

							Mail::to( $user )->send( new UserMail( $data ) );

							DB::commit();

							return response()->json([
				                "result" => 1,
				                "message" => "Te enviamos un correo electronico con tu link para recuperación de contraseña"
				            ], 200 );
						}
						else{

							DB::rollback();

							return response()->json([
								"result" => 2,
								"message" => array( "No se pudo generar la recuperación de contraseña." )
							], 200 );
						}
					}
					catch( \Exception $e ){

						DB::rollback();

						Log::warning( "UserController.forgot() :: " . $e->getMessage() );

						return response()->json([
							"result" => 2,
							"message" => array( "Ocurrió un problema inesperado, revise que todos los campos tienen el formato requerido." )
						], 200 );
					}
				}
			}
		}
		else{

			abort( 404 );
		}
	}

    #Lista de usuarios registrados
    public function lista( Request $request ){

    	$data["site_title"] = "List de usuarios";
    	$data["menu_user"] = true;

    	$data["users"] = User::where([ 'user.status_id' => 1 ])
									->join( "rol", "user.rol_id", "=", "rol.rol_id" )
									->Orderby( "user_creationDate", "asc" )
									->get();
    	return view( "user.list", $data );
    }

    #Cerrar sesion
    public function logout( Request $request ){

		$request->session()->flush();

		return redirect( '/login' );
	}

	#Vista de registro de usuario
	public function register( Request $request ){

		if( $request->session()->get( "us3r-id" ) == 1 ){

			$data["site_title"] = "Registro de usuarios";
			$data["menu_user"] = true;

			$data["rols"] = Rol::where([ "status_id" => 1 ])->Orderby( "rol_name", "asc" )->get();

	    	return view( "user.register", $data );
		}
		else{

			abort( 404 );
		}
	}

	#Función para guardar registro
	public function save( Request $request ){

		if( !$request->ajax() ){

			$message = 	[
							"name.required" => "El nombre(s) es obligatorio",
							"lastName.required" => "El apellido es obligatorio",
							"email.required" => "El email es obligatorio",
							"email.unique" => "El email ya existe",
							"password.required" => "La contraseña es obligatoria",
							"password_confirmed.required" => "La confirmación de contraseña es obligatorio",
							"password_confirmed.same" => "Las contraseñas son diferentes",
							"rol.required" => "El rol es obligatorio",
							"rol.exists" => "El rol no existe",
						];

			$validate = Validator::make($request->all(), [
				"name"	=> "required",
				"lastName"	=> "required",
				"email"		=> "required|email|unique:user,user_email",
				"password"	=> "required",
				"password_confirmed"	=> "required|same:password",
				"rol"	=> "required|exists:rol,rol_encrypted",
			], $message );

			if( $validate->fails() ){

				return redirect( "App/user-register" )->withErrors( $validate )->withInput();
			}
			else{

				$user_name = $request->get( "name" );
				$user_lastName = $request->get( "lastName" );
				$user_email = $request->get( "email" );
				$user_password = md5( $request->get( "password" ) );
				$rol_encrypted = $request->get( "rol" );

				$rol_id = Rol::where( "rol_encrypted", $rol_encrypted )->get();
				$rol_id = $rol_id[0]->rol_id;
				
				DB::beginTransaction();

				try{

					$user_add = new User;
					$user_add->user_name = $user_name;
					$user_add->user_createdBy = $request->session()->get( "us3r-id" );
					$user_add->user_email = $user_email;
					$user_add->user_lastName = $user_lastName;
					$user_add->user_password = $user_password;
					$user_add->rol_id = $rol_id;
					$user_add->user_encrypted = md5( $user_email . date( "Y-m-d H:i:s" ) );
					$user_add->user_creationDate = date( "Y-m-d H:i:s" );
					$user_add->save();

					if( $user_add->id > 0 ){

						DB::commit();

						return redirect( "App/home" );
					}
					else{

						DB::rollback();

						return response()->json([
			                "result" => 2,
			                "message" => array( "Lo sentimos ocurrió un error al querer crear la cuenta" )
			            ], 200 );
					}
				}
				catch( \Exception $e ){

					DB::rollback();

					Log::warning( "UserController.save() :: " . $e->getMessage() );

					$validate->errors()->add( 'field', 'Ocurrió un problema inesperado, revise que todos los campos tienen el formato requerido.' );
					return redirect( "App/user-register" )->withErrors( $validate )->withInput();
				}
			}
		}
		else{

			abort( 404 );
		}
	}

    #Función para actualizar usuario
    public function update( Request $request ){

    	if( !$request->ajax() ){

			$message = 	[
							"id.required" => "El id es obligatorio",
							"name.required" => "El nombre(s) es obligatorio",
							"lastName.required" => "El apellido es obligatorio",
							"email.required" => "El email es obligatorio",
							"email.unique" => "El email ya existe",
							"password.required" => "La contraseña es obligatoria",
							"password_confirmed.required" => "La confirmación de contraseña es obligatorio",
							"password_confirmed.same" => "Las contraseñas son diferentes",
							"rol.required" => "El rol es obligatorio",
							"rol.exists" => "El rol no existe",
						];

			$validate = Validator::make($request->all(), [
				"id"	=> "required|exists:user,user_encrypted",
				"name"	=> "required",
				"lastName"	=> "required",
				"email"		=> "required",
				"password"	=> "required",
				"password_confirmed"	=> "required|same:password",
				"rol"	=> "required|exists:rol,rol_encrypted",
			], $message );

			$user_encrypted = $request->get( "id" );

			if( $validate->fails() ){

				return redirect( "App/user/?id=" . $user_encrypted )->withErrors( $validate )->withInput();
			}
			else{

				$user_name = $request->get( "name" );
				$user_lastName = $request->get( "lastName" );
				$user_email = $request->get( "email" );
				$user_password = md5( $request->get( "password" ) );
				$rol_encrypted = $request->get( "rol" );

				$rol_id = Rol::where( "rol_encrypted", $rol_encrypted )->get();
				$rol_id = $rol_id[0]->rol_id;
				
				DB::beginTransaction();

				try{

					$user_update = User::where( "user_encrypted", $user_encrypted );
					$user_update->update([

						"user_name" => $user_name,
						"user_email" => $user_email,
						"user_lastName" => $user_lastName,
						"user_password" => md5( $user_password ),
						"rol_id" => $rol_id,
						"user_lastModification" => date( "Y-m-d H:i:s" )
					]);

					if( $user_update ){

						DB::commit();

						return redirect( "App/user/?id=" . $user_encrypted );
					}
					else{

						DB::rollback();

						return response()->json([
			                "result" => 2,
			                "message" => array( "Lo sentimos ocurrió un error al querer crear la cuenta" )
			            ], 200 );
					}
				}
				catch( \Exception $e ){

					DB::rollback();

					Log::warning( "UserController.update() :: " . $e->getMessage() );

					$validate->errors()->add( 'field', 'Ocurrió un problema inesperado, revise que todos los campos tienen el formato requerido.' );
					return redirect( "App/user/?id=" . $user_encrypted )->withErrors( $validate )->withInput();
				}
			}
		}
		else{

			abort( 404 );
		}
    }

    #Validamos sesion del usuario
    public function validation_login( Request $request ){

		if( !$request->ajax() ){

			$message = 	[
							"email.required" => "El email es obligatorio",
							"email.email" => "El email no está en un formato válido",
							"email.exists" => "El email no existe",
							"password.required" => "La contraseña es obligatoria",
						];

			$validate = Validator::make($request->all(), [
				"email" => "required|email|exists:user,user_email",
				"password" => "required",
			], $message );

			if( $validate->fails() ){

				return redirect( "/login" )->withErrors( $validate )->withInput();
			}
			else{

				$user_password = md5( $request->get( "password" ) );
				$user_email = $request->get( "email" );

				$result = User::where([ 'user.status_id' => 1,
										'user.user_password' => $user_password,
										'user.user_email' => $user_email
									])
									->join( "rol", "user.rol_id", "=", "rol.rol_id" )
									->get();

				if( count( $result ) > 0 ){

					DB::beginTransaction();

					try{

						$request->session()->put( [ 
							"us3r-id" => $result[0]->user_id,
							"us3r-name" => $result[0]->user_name,
							"us3r-lastName" => $result[0]->user_lastName,
							"us3r-email" => $result[0]->user_email,
							"us3r-rol" => $result[0]->rol_id,
							"us3r-rol_name" => $result[0]->rol_name,
							"us3r-encrypted" => $result[0]->user_encrypted
						] );

						$session = $request->session()->all();

						$session_add = new User_Session;
						$session_add->user_id = $result[0]->user_id;
						$session_add->userSession_encrypted = $session["_token"];
						$session_add->userSession_ip = $request->ip();
						$session_add->userSession_creationDate = date( "Y-m-d H:i:s" );
						$session_add->save();

						if( $session_add->id > 0 ){

							$request->session()->put( [ 
								"us3r-session" => $session_add->id
							] );

							DB::commit();

							return redirect( "App/home" );
						}
						else{

							DB::rollback();

							$request->session()->forget(['us3r-id', 'us3r-email', 'us3r-rol', 'us3r-encrypted' ]);

							$validate->errors()->add( 'field', 'Ocurrió un problema inesperado, contactenos.' );
							return redirect( "/login" )->withErrors( $validate )->withInput();
						}
					}
					catch( \Exception $e ){

						DB::rollback();

						Log::warning( "UserController:validation_login() => " . $e->getMessage() );

						$validate->errors()->add( 'field', 'Ocurrió un problema inesperado, revise que todos los campos tienen el formato requerido.' );
						
						return redirect( "/login" )->withErrors( $validate )->withInput();
					}
				}
				else{

					$validate->errors()->add( 'field', 'Los datos son incorrectos' );
					return redirect( "/login" )->withErrors( $validate )->withInput();
				}
			}
		}
		else{

			abort( 404 );
		}
	}
}