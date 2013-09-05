<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('sign-in',     'SessionsController@getNew');
Route::post('sign-in',    'SessionsController@postCreate');
Route::delete('sign-out', 'SessionsController@deleteDestroy');

Route::get('users/{username}', 'UsersController@getShow');

/**
 * Routes for members
 */
Route::group(['before' => 'auth.member', 'prefix' => 'dashboard'], function() {
	Route::controller('lockers', 'LockersController');
});

/**
 * Routes for admins
 */
Route::group(['before' => 'auth.admin', 'prefix' => 'admin'], function() {

	Route::get('/', 'Admin\DashboardController@getShow');

	// Categories
	Route::resource('categories', 'Admin\CategoriesController', ['except' => ['show']]);

	// Contents
	Route::resource('contents', 'Admin\ContentsController', ['except' => ['show']]);

	// Pages
	Route::resource('pages', 'Admin\PagesController', ['except' => ['show']]);

	// Users
	Route::controller('users/eactivities', 'Admin\UsersEActivitiesController');
	Route::controller('users/eepeople',    'Admin\UsersEEPeopleController');
	Route::controller('users',             'Admin\UsersController');
	Route::resource  ('users',             'Admin\UsersController', ['only' => ['index']]);

	// elFinder
	Route::get('elfinder',           'TSF\ElfinderLaravel\ElfinderController@showIndex');
	Route::any('elfinder/connector', 'TSF\ElfinderLaravel\ElfinderController@showConnector');
	Route::get('elfinder/ckeditor',  'TSF\ElfinderLaravel\ElfinderController@showCKEditor');
});

// Catch all
Route::any('{path}', function($path) {
	$path = rtrim($path, '/');
	$page = Page::where('slug', '=', $path)->first();

	if ( ! $page) {
		App::abort(404);
	}

	return View::make('page')
		->with('page', $page);
})->where('path', '.*');