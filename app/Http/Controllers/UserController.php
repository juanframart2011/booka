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

    	return view( "login" );
    }
}
