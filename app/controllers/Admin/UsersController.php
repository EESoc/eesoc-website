<?php
namespace Admin;

use Auth;
use Input;
use Redirect;
use User;
use View;

class UsersController extends BaseController {

	const USERS_PER_PAGE = 20;

	/**
	 * Display a list of users. Able to apply filters and search query.
	 *
	 * @return Response
	 */
	public function index()
	{
		$user_instance = new User;
		$users_query = $user_instance->newQuery();

		$request_params = array(
			'filter' => Input::get('filter'),
			'query'  => Input::get('query'),
		);

		if ( ! empty($request_params['filter'])) {
			if (isset(User::$FILTER_TO_FUNCTION_MAP[$request_params['filter']])) {
				$users_query->{User::$FILTER_TO_FUNCTION_MAP[$request_params['filter']]}();
			} else {
				$request_params['filter'] = null;
			}
		}

		if ( ! empty($request_params['query'])) {
			$users_query->searching($request_params['query']);
		}

		$users_query->adminsFirst();

		return View::make('admin.users.index')
			->with('users', $users_query->paginate(self::USERS_PER_PAGE))
			->with(User::statistics())
			->with('paginator_appends', $request_params);
	}

	/**
	 * Promotes a user to admin.
	 *
	 * @return Response
	 */
	public function putPromote($username)
	{
		$user = User::where('username', '=', $username)->firstOrFail();
		
		if ($user->id === Auth::user()->id) {
			return Redirect::back()
				->with('danger', 'You cannot promote yourself');
		} else if ($user->is_admin) {
			return Redirect::back()
				->with('danger', "{$user->username} is already an Admin");
		} else {
			$user->is_admin = true;
			$user->save();

			return Redirect::back()
				->with('success', "{$user->username} has been successfully promoted to Admin");
		}
	}

	/**
	 * Demotes a user from admin.
	 *
	 * @return Response
	 */
	public function putDemote($username)
	{
		$user = User::where('username', '=', $username)->firstOrFail();
		
		if ($user->id === Auth::user()->id) {
			return Redirect::back()
				->with('danger', 'You cannot demote yourself');
		} else if ( ! $user->is_admin) {
			return Redirect::back()
				->with('danger', "{$user->username} is already a Non-Admin");
		} else {
			$user->is_admin = false;
			$user->save();

			return Redirect::back()
				->with('success', "{$user->username} has been successfully demoted from Admin");
		}

	}

}