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

Route::controller('cron', 'CronController');
Route::controller('newsletters', 'NewslettersController');

/**
 * Routes for members
 */
Route::group(['before' => 'auth.member'], function() {
	// Dashboard
	Route::get('dashboard', 'UsersController@getDashboard');

	Route::group(['prefix' => 'dashboard'], function() {
		// Lockers
		// Route::controller('lockers', 'LockersController');
		// Temporary route
		Route::get('lockers', function() {
			$page = Page::where('slug', '=', 'dashboard/lockers')->first();

			if ( ! $page) {
				App::abort(404);
			}

			return View::make('page')
				->with('page', $page);
		})->where('path', '.*');
	});
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

	// Emails
	Route::resource('emails', 'Admin\EmailsController');
	Route::controller('emails', 'Admin\EmailsController');

	// Events
	Route::resource('events', 'Admin\EventsController', ['except' => ['show']]);

	// Logs
	Route::resource('logs', 'Admin\LogsController', ['only' => ['index', 'show']]);

	// Pages
	Route::resource('pages', 'Admin\PagesController', ['except' => ['show']]);

	// Posts
	Route::resource('posts', 'Admin\PostsController', ['except' => ['show']]);

	// Users
	Route::controller('users/eactivities', 'Admin\UsersEActivitiesController');
	Route::controller('users/eepeople',    'Admin\UsersEEPeopleController');
	Route::controller('users',             'Admin\UsersController');
	Route::resource  ('users',             'Admin\UsersController', ['only' => ['index']]);

	// elFinder
	Route::get('elfinder',           'Barryvdh\ElfinderBundle\ElfinderController@showIndex');
	Route::any('elfinder/connector', 'Barryvdh\ElfinderBundle\ElfinderController@showConnector');
	Route::get('elfinder/ckeditor',  'Barryvdh\ElfinderBundle\ElfinderController@showCKEditor');
});

Route::controller('home', 'HomeController');
Route::get('/', 'HomeController@getWelcome');

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