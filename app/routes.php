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

Route::group(array('prefix' => 'admin'), function() {

	Route::get('/', 'Admin\DashboardController@getShow');

	Route::resource('categories', 'Admin\CategoriesController', array('except' => array('show')));

	Route::controller('users/eactivities', 'Admin\UsersEActivitiesController');
	Route::controller('users', 'Admin\UsersController');
	Route::resource('users', 'Admin\UsersController', array('only' => array('index')));

});

Route::get('/', function() {
	return View::make('hello');
});

Route::any('{path}', function($path) {
	return View::make('hello');
})->where('path', '.*');