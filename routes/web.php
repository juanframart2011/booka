<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get( '/', [ "as" => "login", "uses" => "UserController@home" ] );
Route::get( 'forgot/{url}', [ "uses" => "UserController@change_password" ] );
Route::post( 'forgot', [ "as" => "forgot", "uses" => "UserController@change_password_action" ] );
Route::get( 'login', [ "as" => "login", "uses" => "UserController@home" ] );
Route::post( 'login', [ "as" => "login", "uses" => "UserController@validation_login" ] );
Route::post( 'recovery', [ "as" => "recovery", "uses" => "UserController@forgot" ] );

Route::group(['prefix' => 'App', "middleware" => "userPermission"], function(){//Parametro con el cual validar

	#Rutas usuarios
	require __DIR__ . '/user.php';

	#Rutas libro
	require __DIR__ . '/book.php';
});