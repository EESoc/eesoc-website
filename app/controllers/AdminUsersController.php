<?php

class AdminUsersController extends AdminController {

	const USERS_PER_PAGE = 20;

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$userInstance = new User;
		$usersQuery = $userInstance->newQuery();

		$paginator_appends = array(
			'filter' => Input::get('filter')
		);
		switch ($paginator_appends['filter']) {
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
			default:
				$paginator_appends['filter'] = null;
				break;
		}

		return View::make('admin.users.index')
			->with('users', $usersQuery->paginate(self::USERS_PER_PAGE))
			->with('everybody_count', User::count())
			->with('admins_count', User::admin()->count())
			->with('non_admins_count', User::nonAdmin()->count())
			->with('paginator_appends', $paginator_appends);
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