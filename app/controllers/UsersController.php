<?php

class UsersController extends BaseController {

	/**
	 * Display a user's public profile.
	 * @param  string $username
	 * @return Response
	 */
	public function getShow($username)
	{
		$user = User::where('username', '=', $username)->firstOrFail();
		return View::make('users.show')
			->with('user', $user);
	}

	/**
	 * Display signed in user's dashboard.
	 * @return Response
	 */
	public function getDashboard()
	{
		return View::make('users.show')
			->with('user', Auth::user());
	}

}