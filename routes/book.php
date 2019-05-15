<?php
Route::get( 'book/{id}', [ "as" => "book/{id}", "uses" => "BookController@detail" ] );

Route::get( 'home', [ "as" => "home", "uses" => "BookController@lista" ] );

Route::get( 'book-register', [ "as" => "book-register", "uses" => "BookController@register" ] );

Route::post( 'book-save', [ "as" => "book-save", "uses" => "BookController@save" ] );