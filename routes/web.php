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


// Blog API Routes
Route::prefix('blogapi')->group(function () {  // we prefix that all the API URLs starts with /blogapi/...

	// Initialize the Blog Application
	Route::get('init', 'BlogController@init');  
	//example: http://localhost:8000/blogapi/init 
	
	// create new Blog
	Route::get('create/{title}/{author}/{content}/{published?}', [ 
	    'as' => 'newblog', 'uses' => 'BlogController@create'
	]);
	//example: http://localhost:8000/blogapi/create/newTitle/someone/blablabla/true?sports&fashion
	
	// update a Blog by blogId
	Route::get('update/{id}/{title}/{author}/{content}/{published?}', 'BlogController@update');
	// example:  http://localhost:8000/blogapi/update/3/aaa/bbb/ccc/true?sports

	// Delete a Blog by blogId
	Route::get('delete/{id}', 'BlogController@delete');
	// http://localhost:8000/blogapi/delete/2

	// Get all Blogs  - We can apply many filters on this: check BlogController->allMethod or README file
	Route::get('blogs/{order?}/{published?}', 'BlogController@all');
	
	// Get a Blog Object by its ID in json format
	Route::get('blog/{id}', 'BlogController@blog')->name('blog.show'); // used to produce a link for the blog in the Email sent to Admin

	// note: Laravel suggests for making APIs to use the routes/api.php file but there is not possible the use of Sessions)
});


