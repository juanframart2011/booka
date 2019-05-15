<?php
Route::get( 'book', [ "as" => "book", "uses" => "BookController@detail" ] );

Route::get( 'book-home', [ "as" => "book-home", "uses" => "BookController@lista" ] );

Route::get( 'book-delete', [ "as" => "book-delete", "uses" => "BookController@delete" ] );

Route::get( 'book-register', [ "as" => "book-register", "uses" => "BookController@register" ] );

Route::post( 'book-save', [ "as" => "book-save", "uses" => "BookController@save" ] );

Route::post( 'book-update', [ "as" => "book-update", "uses" => "BookController@update" ] );