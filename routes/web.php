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

Route::get('/', 'HomeController@index');

Route::post('/addTag','HomeController@create_tag' );

Route::post('/removeTag','HomeController@remove_tag' );

Route::post('/editTag', 'HomeController@edit_tag');
