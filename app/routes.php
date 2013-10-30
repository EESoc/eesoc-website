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

# Home
Route::controller('home', 'HomeController');
Route::get('/', 'HomeController@getWelcome');

# Session Management
Route::get('sign-in',     'SessionsController@getNew');
Route::post('sign-in',    'SessionsController@postCreate');
Route::delete('sign-out', 'SessionsController@deleteDestroy');

# Cron
Route::controller('cron', 'CronController');

# Emails
Route::get('emails/track/{tracker_token}.gif', 'EmailsController@getTrack');
Route::controller('emails', 'EmailsController');

# Events
Route::controller('events', 'EventsController');

# Newsletter
Route::controller('newsletters', 'NewslettersController');

# Sponsors
Route::controller('sponsors', 'SponsorsController');

# User
Route::get('users/{username}', 'UsersController@getShow');


/**
 * Routes for members
 */
Route::group(['before' => 'auth.member'], function() {
	# Dashboard
	Route::get('dashboard', 'UsersController@getDashboard');

	Route::group(['prefix' => 'dashboard'], function() {
		# Books
		Route::resource  ('books', 'BooksController');
		Route::controller('books', 'BooksController');

		# Lockers
		Route::controller('lockers', 'LockersController');
	});
});

/**
 * Routes for admins
 */
Route::group(['before' => 'auth.admin', 'prefix' => 'admin'], function() {

	Route::get('/', 'Admin\DashboardController@getShow');

	# Categories
	Route::resource('categories', 'Admin\CategoriesController', ['except' => ['show']]);

	# Contents
	Route::resource('contents', 'Admin\ContentsController', ['except' => ['show']]);

	# Emails
	Route::resource('emails', 'Admin\EmailsController');
	Route::controller('emails', 'Admin\EmailsController');

	# Events
	Route::resource('events', 'Admin\EventsController', ['except' => ['show']]);
	Route::controller('events', 'Admin\EventsController');

	# Instagram Photos
	Route::controller('instagram-photos', 'Admin\InstagramPhotosController');

	# Logs
	Route::resource('logs', 'Admin\LogsController', ['only' => ['index', 'show']]);

	# Newsletters
	Route::resource('newsletters', 'Admin\NewslettersController');

	# Pages
	Route::resource('pages', 'Admin\PagesController', ['except' => ['show']]);

	# Posts
	Route::resource('posts', 'Admin\PostsController', ['except' => ['show']]);

	# Sales
	Route::resource('sales', 'Admin\SalesController', ['only' => ['index']]);

	# Sponsors
	Route::resource('sponsors', 'Admin\SponsorsController', ['except' => ['show']]);

	# Tests
	Route::controller('tests', 'Admin\TestsController');

	# User Sign Ins
	Route::resource('user-sign-ins', 'Admin\UserSignInsController', ['only' => ['index']]);

	# Users
	Route::controller('users/eactivities', 'Admin\UsersEActivitiesController');
	Route::controller('users/eepeople',    'Admin\UsersEEPeopleController');
	Route::controller('users',             'Admin\UsersController');
	Route::resource  ('users',             'Admin\UsersController', ['only' => ['index']]);

	# elFinder
	Route::get('elfinder',           'Barryvdh\ElfinderBundle\ElfinderController@showIndex');
	Route::any('elfinder/connector', 'Admin\ElfinderController@showConnector');
	Route::any('elfinder/connector', 'Barryvdh\ElfinderBundle\ElfinderController@showConnector');
	Route::get('elfinder/ckeditor',  'Barryvdh\ElfinderBundle\ElfinderController@showCKEditor');
});

# Catch all
Route::any('{path}', function($path) {
	$path = rtrim($path, '/');
	$page = Page::where('slug', '=', $path)->first();

	if ( ! $page) {
		App::abort(404);
	}

	return View::make('page')
		->with('page', $page);
})->where('path', '.*');