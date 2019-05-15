<?php
Route::get( 'user', [ "as" => "user", "uses" => "UserController@detail" ] );

Route::get( 'home', [ "as" => "home", "uses" => "UserController@lista" ] );

Route::get( 'logout', [ "as" => "logout", "uses" => "UserController@logout" ] );

Route::get( 'user-delete', [ "as" => "user-delete", "uses" => "UserController@delete" ] );

Route::get( 'user-register', [ "as" => "user-register", "uses" => "UserController@register" ] );

Route::post( 'user-save', [ "as" => "user-save", "uses" => "UserController@save" ] );

Route::post( 'user-update', [ "as" => "user-update", "uses" => "UserController@update" ] );