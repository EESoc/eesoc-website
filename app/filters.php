<?php

/*
|--------------------------------------------------------------------------
| Application & Route Filters
|--------------------------------------------------------------------------
|
| Below you will find the "before" and "after" events for the application
| which may be used to do any work before or after a request into your
| application. Here you may also register your custom route filters.
|
*/

App::before(function($request)
{
	//
});


App::after(function($request, $response)
{
	//
});

/*
|--------------------------------------------------------------------------
| Authentication Filters
|--------------------------------------------------------------------------
|
| The following filters are used to verify that the user of the current
| session is logged into this application. The "basic" filter easily
| integrates HTTP Basic authentication for quick, simple checking.
|
*/

Route::filter('auth', function()
{
	if (Auth::guest())
	{
		$signInURL = URL::action('SessionsController@getNew');
		return Redirect::guest($signInURL);
	}
});


Route::filter('auth.member', function()
{
	if (Auth::guest())
	{
		$signInURL = URL::action('SessionsController@getNew');
		return Redirect::guest($signInURL)->with('info', 'You need to be signed in before you can continue');
	}

	if ( ! Auth::user()->is_member)
	{
		// @todo redirect to a nice page stating that you are not a member and that he/she should get membership
		App::abort(401, 'You are not a member of the society');
	}
});


Route::filter('auth.admin', function()
{
	if (Auth::guest())
	{
		$signInURL = URL::action('SessionsController@getNew');
		return Redirect::guest($signInURL)->with('info', 'You need to be signed in before you can continue');
	}

	if ( ! Auth::user()->is_admin)
	{
		App::abort(401, 'You are not authorized');
	}
});


Route::filter('auth.basic', function()
{
	return Auth::basic();
});

/*
|--------------------------------------------------------------------------
| Guest Filter
|--------------------------------------------------------------------------
|
| The "guest" filter is the counterpart of the authentication filters as
| it simply checks that the current user is not logged in. A redirect
| response will be issued if they are, which you may freely change.
|
*/

Route::filter('guest', function()
{
	if (Auth::check()) return Redirect::to('/');
});

/*
|--------------------------------------------------------------------------
| CSRF Protection Filter
|--------------------------------------------------------------------------
|
| The CSRF filter is responsible for protecting your application against
| cross-site request forgery attacks. If this special token in a user
| session does not match the one given in this request, we'll bail.
|
*/

Route::filter('csrf', function()
{
	if (Session::token() != Input::get('_token'))
	{
		throw new Illuminate\Session\TokenMismatchException;
	}
});