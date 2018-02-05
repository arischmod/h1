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

Route::get('/', function () {
    return view('welcome');
});

Route::get('retrieve', 'BlogController@retrieve');


Route::get('init', 'BlogController@init');
Route::get('blogs/{order?}/{published?}', 'BlogController@all');
Route::get('blog/{id}', 'BlogController@blog');
Route::get('delete/{id}', 'BlogController@delete');
Route::get('update/{id}/{title?}/{author?}/{content?}/{published?}', 'BlogController@update');


Route::get('newblog/{title?}/{author?}/{content?}/{published?}', [
    'as' => 'newblog', 'uses' => 'BlogController@create'
]);


Route::get('flush', function () {
    return Session::flush();    
});
 