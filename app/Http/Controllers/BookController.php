<?php
namespace App\Http\Controllers;

use App\Book;
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

    	$id = $request->get( "id" );
    	if( empty( $id ) ){

			abort( 404 );
		}
		else{

			$book = Book::where([ 'book.book_encrypted' => $id ]);
    		
    		$book->update([ "status_id" => 2]);

    		return redirect( "App/book-home" );
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

    #Lista de usuarios registrados
    public function lista( Request $request ){

    	$data["site_title"] = "Lista de libros";
    	$data["menu_book"] = true;
    	$data["menu_book_list"] = true;

    	$data["books"] = Book::where([ 'book.status_id' => 1 ])
									->leftJoin( "book_lend", "book.book_id", "=", "book_lend.book_id" )
									->leftJoin( "status_lend", "book_lend.statusLend_id", "=", "status_lend.statusLend_id" )
									->Orderby( "book.book_creationDate", "asc" )
									->get();
    	return view( "book.list", $data );
    }

	#Vista de registro de usuario
	public function register( Request $request ){

		if( $request->session()->get( "us3r-id" ) == 1 ){

			$data["site_title"] = "Registro de Libro";
			$data["menu_book"] = true;

			return view( "book.register", $data );
		}
		else{

			abort( 404 );
		}
	}

	#Función para guardar registro
	public function save( Request $request ){

		if( !$request->ajax() ){

			$message = 	[
							"name.required" => "El nombre es obligatorio",
							"autor.required" => "El autor) es obligatorio",
							"name.unique" => "El nombre ya existe",
							"description.required" => "La descripción es obligatoria",
							"created.required" => "La fecha de publicación es obligatoria",
							"media.required" => "La contraseña es obligatoria",
						];

			$validate = Validator::make($request->all(), [
				"name"	=> "required|unique:book,book_name",
				"description"	=> "required",
				"created"		=> "required",
				"autor"	=> "required",
				"media"	=> "required",
			], $message );

			if( $validate->fails() ){

				return redirect( "App/book-register" )->withErrors( $validate )->withInput();
			}
			else{

				$book_name = $request->get( "name" );
				$book_description = $request->get( "description" );
				$book_created = $request->get( "created" );
				$book_autor = $request->get( "autor" );
				$media = $request->file( "media" );

				$book_url = str_slug( $book_name, "-" );

				DB::beginTransaction();

				try{

					$ruta_media = public_path( 'img/book/' );
					$imagen_name = str_slug( $book_url, "-" );
					
					$book_media = 'book/' . $imagen_name . '.png';

					$media->move( $ruta_media, $imagen_name . '.png' );
					
					$book_add = new Book;
					$book_add->book_name = $book_name;
					$book_add->book_createdBy = $request->session()->get( "us3r-id" );
					$book_add->book_description = $book_description;
					$book_add->book_url = $book_url;
					$book_add->book_autor = $book_autor;
					$book_add->book_created = $book_created;
					$book_add->book_media = $book_media;
					$book_add->book_encrypted = md5( $book_name . date( "Y-m-d H:i:s" ) );
					$book_add->book_creationDate = date( "Y-m-d H:i:s" );
					$book_add->save();

					if( $book_add->id > 0 ){

						DB::commit();

						return redirect( "App/book-home" );
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

					Log::warning( "BookController.save() :: " . $e->getMessage() );

					$validate->errors()->add( 'field', 'Ocurrió un problema inesperado, revise que todos los campos tienen el formato requerido.' );
					return redirect( "App/book-register" )->withErrors( $validate )->withInput();
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

			$book_encrypted = $request->get( "id" );

			if( $validate->fails() ){

				return redirect( "App/user/?id=" . $book_encrypted )->withErrors( $validate )->withInput();
			}
			else{

				$book_name = $request->get( "name" );
				$book_lastName = $request->get( "lastName" );
				$book_email = $request->get( "email" );
				$book_password = md5( $request->get( "password" ) );
				$rol_encrypted = $request->get( "rol" );

				$rol_id = Rol::where( "rol_encrypted", $rol_encrypted )->get();
				$rol_id = $rol_id[0]->rol_id;
				
				DB::beginTransaction();

				try{

					$book_update = User::where( "user_encrypted", $book_encrypted );
					$book_update->update([

						"user_name" => $book_name,
						"user_email" => $book_email,
						"user_lastName" => $book_lastName,
						"user_password" => md5( $book_password ),
						"rol_id" => $rol_id,
						"user_lastModification" => date( "Y-m-d H:i:s" )
					]);

					if( $book_update ){

						DB::commit();

						return redirect( "App/user/?id=" . $book_encrypted );
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

					Log::warning( "BookController.update() :: " . $e->getMessage() );

					$validate->errors()->add( 'field', 'Ocurrió un problema inesperado, revise que todos los campos tienen el formato requerido.' );
					return redirect( "App/user/?id=" . $book_encrypted )->withErrors( $validate )->withInput();
				}
			}
		}
		else{

			abort( 404 );
		}
    }
}