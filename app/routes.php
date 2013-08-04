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

Route::get('sign_in', 'SessionsController@getNew');
Route::post('sign_in', array('before' => 'csrf', 'uses' => 'SessionsController@postCreate'));

Route::get('users/{username}', 'UsersController@getShow');

Route::group(array('prefix' => 'admin'), function() {



});

Route::get('/', function()
{
	return View::make('hello');
});