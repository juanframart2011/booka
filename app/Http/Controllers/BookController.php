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

    #Función para eliminado lógico de Libro
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

    #Función que muestra detalle de Libro
    public function detail( Request $request ){

    	$id = $request->get( "id" );
    	if( empty( $id ) ){

			abort( 404 );
		}
		else{

			$data["site_title"] = "Detalle de libro";
			$data["menu_book"] = true;
			$book = Book::where([ 'book.status_id' => 1 ])
									->leftJoin( "book_lend", "book.book_id", "=", "book_lend.book_id" )
									->leftJoin( "status_lend", "book.statusLend_id", "=", "status_lend.statusLend_id" )
									->where( "book.book_encrypted", $id )
									->get();
    		$data["book"] = $book[0];
    		$data["book_lend"] = Book_Lend::where([
    								"book_lend.book_id" => $book[0]->book_id
    							])
    							->join( "user", "book_lend.user_id", "=", "user.user_id" )
    							->join( "book", "book_lend.book_id", "=", "book.book_id" )
    							->join( "status_lend", "book.statusLend_id", "=", "status_lend.statusLend_id" )
    							->orderBy( "book_lend.bookLend_creationDate", "desc" )
    							->get();

    		$data["book_status_lend"] = Book_Lend::where([
    								"book_lend.status_id" => 1,
    								"book_lend.book_id" => $book[0]->book_id
    							])
    							->limit( 1 )
    							->orderBy( "book_lend.bookLend_creationDate", "desc" )
    							->get();

    		return view( "book.detail", $data );
		}
    }

    #Función para prestar libro
    public function lend( Request $request ){

    	$id = $request->get( "id" );
    	if( empty( $id ) ){

			abort( 404 );
		}
		else{

			$book = Book::where([ 'book.book_encrypted' => $id ])->get();

			if( count( $book ) == 0 ){

				return redirect( "App/book-home" );
			}
			else{

				DB::beginTransaction();

				try{

					$book_id = $book[0]->book_id;
					$date_modification = date( "Y-m-d H:i:s");
	    		
		    		$bookLend_new = new Book_Lend;
		    		$bookLend_new->book_id = $book_id;
		    		$bookLend_new->user_id = $request->session()->get( "us3r-id" );
		    		$bookLend_new->bookLend_encrypted = md5( $book_id . $request->session()->get( "us3r-id" ) . date( "YmdHis") );
		    		$bookLend_new->bookLend_creationDate = $date_modification;
		    		$bookLend_new->save();

					if( $bookLend_new->id > 0 ){

						$book_update = Book::where("book_id",$book_id);
						$book_update->update([
							"statusLend_id" => 1,
							"book_lastModification" => $date_modification
						]);
						DB::commit();

						return redirect( "App/book/?id=" . $id );
					}
					else{

						DB::rollback();

						return redirect( "App/book-home" );
					}
				}
				catch( \Exception $e ){

					DB::rollback();

					Log::warning( "BookController.lend() :: " . $e->getMessage() );

					return redirect( "App/book-home" );
				}
	    	}
		}
    }


    #Función donde se cambia el estatus de un prestamo de libro
    public function lend_update( Request $request ){

    	$id = $request->get( "id" );
    	$status = $request->get( "status" );

    	if( empty( $id ) || empty( $status ) ){

			abort( 404 );
		}
		else{

			$bookLend_update = Book_Lend::where([ 'bookLend_encrypted' => $id ]);
			$book = $bookLend_update->get();
			$book_id = $book[0]->book_id;

			if( $bookLend_update->count() == 0 ){

				return redirect( "App/book-home" );
			}
			else{

				DB::beginTransaction();

				try{

					$date_mod = date( "Y-m-d H:i:s");

					$book_update = Book::where( "book_id", $book_id );
					$book_reload = $book_update->get();

					$bookLend_update->update([
						"bookLend_lastModification" => $date_mod
					]);

					$book_update->update([
						"statusLend_id" => $status,
						"book_lastModification" => $date_mod
					]);

					if( $bookLend_update ){

						DB::commit();

						return redirect( "App/book/?id=" . $book_reload[0]->book_encrypted );
					}
					else{

						DB::rollback();

						return redirect( "App/book-home" );
					}
				}
				catch( \Exception $e ){

					DB::rollback();

					Log::warning( "BookController.lend_update() :: " . $e->getMessage() );

					return redirect( "App/book-home" );
				}
	    	}
		}
    }

    #Lista de libros registrados
    public function lista( Request $request ){

    	$data["site_title"] = "Lista de libros";
    	$data["menu_book"] = true;
    	$data["menu_book_list"] = true;

    	$data["books"] = Book::where([ 'book.status_id' => 1 ])
									->leftJoin( "status_lend", "book.statusLend_id", "=", "status_lend.statusLend_id" )
									->Orderby( "book.book_creationDate", "asc" )
									->get();
    	return view( "book.list", $data );
    }

	#Vista de registro de Libro
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
							"media.required" => "La imagen es obligatoria",
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

    #Función para actualizar Libro
    public function update( Request $request ){

    	if( !$request->ajax() ){

			$message = 	[
							"name.required" => "El nombre es obligatorio",
							"autor.required" => "El autor) es obligatorio",
							"name.unique" => "El nombre ya existe",
							"description.required" => "La descripción es obligatoria",
							"created.required" => "La fecha de publicación es obligatoria",
						];

			$validate = Validator::make($request->all(), [
				"name"	=> "required|unique:book,book_name",
				"description"	=> "required",
				"created"		=> "required",
				"autor"	=> "required",
			], $message );

			$book_encrypted = $request->get( "id" );

			if( $validate->fails() ){

				return redirect( "App/book/?id=" . $book_encrypted )->withErrors( $validate )->withInput();
			}
			else{

				$book_name = $request->get( "name" );
				$book_description = $request->get( "description" );
				$book_created = $request->get( "created" );
				$book_autor = $request->get( "autor" );
				$media = $request->file( "media" );

				$book_url = str_slug( $book_name, "-" );
				$book_select = Book::where( "book_encrypted", $book_encrypted )->get();
				$book_update = Book::where( "book_encrypted", $book_encrypted );

				
				if( !empty( $media ) ){

					$imagen_name = str_slug( $book_url, "-" );
					
					$ruta_media = public_path( 'img/book/' );
					$book_media = 'book/' . $imagen_name . '.png';
					$media->move( $ruta_media, $imagen_name . '.png' );
				}
				else{

					$book_media = $book_select[0]->book_media;
				}

				$book_update->update([

					"book_name" => $book_name,
					"book_description" => $book_description,
					"book_autor" => $book_autor,
					"book_created" => $book_created,
					"book_media" => $book_media,
					"book_lastModification" => date( "Y-m-d H:i:s" )
				]);
				
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