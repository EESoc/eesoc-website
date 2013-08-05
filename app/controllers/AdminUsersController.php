<?php

class AdminUsersController extends AdminController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$userInstance = new User;
		$usersQuery = $userInstance->newQuery();

		switch (Input::get('filter')) {
			case 'admins':
				$usersQuery->admin();
				break;
			case 'non-admins':
				$usersQuery->nonAdmin();
				break;
			case 'members':
				// @todo
				break;
			case 'non-members':
				// @todo
				break;
		}

		return View::make('admin.users.index')
			->with('users', $usersQuery->get())
			->with('everybody_count', User::count())
			->with('admins_count', User::admin()->count())
			->with('non_admins_count', User::nonAdmin()->count());
	}

	public function getSynchronize()
	{
		
	}

	public function putSynchronize()
	{
		$user = User::where('username', '=', $username)->firstOrFail();
		echo 'do something';
	}

	public function putPromote($username)
	{
		$user = User::where('username', '=', $username)->firstOrFail();
		
		if ($user->id === Auth::user()->id) {
			return Redirect::back()->with('danger', 'You cannot promote yourself');
		} else if ($user->isAdmin()) {
			return Redirect::back()->with('danger', "{$user->username} is already an Admin");
		} else {
			$user->is_admin = true;
			$user->save();

			return Redirect::back()->with('success', "{$user->username} has been successfully promoted to Admin");
		}
	}

	public function putDemote($username)
	{
		$user = User::where('username', '=', $username)->firstOrFail();
		
		if ($user->id === Auth::user()->id) {
			return Redirect::back()->with('danger', 'You cannot demote yourself');
		} else if ( ! $user->isAdmin()) {
			return Redirect::back()->with('danger', "{$user->username} is already a Non-Admin");
		} else {
			$user->is_admin = false;
			$user->save();

			return Redirect::back()->with('success', "{$user->username} has been successfully demoted from Admin");
		}

	}

}