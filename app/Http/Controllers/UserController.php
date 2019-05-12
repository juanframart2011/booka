<?php

namespace App\Http\Controllers;

use App\Status;
use App\User;
use App\User_Recovery;
use App\User_Session;

use Illuminate\Http\Request;

class UserController extends Controller
{
    
    #Login
    public function home( Request $request ){

    	$user = $request->session()->get( "user" );

    	if( !empty( $user ) ){

    		return redirect( "home" );
    	}
    	else{

    		return view( "login" );
    	}
    }

    #Función para eliminado lógico de usuario
    public function delete( Request $request ){


    }

    #Función que muestra detalle de usuario
    public function detail( Request $requst, $user ){

    	if( empty( $user ) ){

			abort( 404 );
		}
		else{
			
		}
    }

    #Lista de usuarios registrados
    public function lista( Request $request ){

    	$data["users"] = User::Where( "status_id", 1 )->Orderby( "user_name", "asc" )->get();
    	return view( "list", $data );
    }

    #Función para actualizar usuario
    public function delete( Request $request ){

    	
    }

    #Validamos sesion del usuario
    public function validation( Request $request ){


    }
}
