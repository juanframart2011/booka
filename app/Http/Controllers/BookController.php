<?php
namespace App\Http\Controllers;

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

class BookController extends Controller
{

    #Función para eliminado lógico de usuario
    public function delete( Request $request ){


    }

    #Función que muestra detalle de usuario
    public function detail( Request $request, $id ){

    	if( empty( $id ) ){

			abort( 404 );
		}
		else{

			$data["site_title"] = "Registro de usuarios";
			$data["menu_user"] = true;

			$user = User::where([ 'user.user_encrypted' => $id ])
									->join( "rol", "user.rol_id", "=", "rol.rol_id" )
									->Orderby( "user_creationDate", "asc" )
									->get();
    		$data["user"] = $user[0];
    		$data["book_lend"] = Book_Lend::where([ "status_id" => 1, "user_id" => $user[0]->user_id ])->get();

    		return view( "user.detail", $data );
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
    public function update( Request $request, $user ){

    	
    }
}