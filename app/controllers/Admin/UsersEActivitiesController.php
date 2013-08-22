<?php
namespace Admin;

use App;
use Auth;
use EActivitiesClient;
use Guzzle\Http\Client as HttpClient;
use Input;
use Redirect;
use Session;
use User;
use Validator;
use View;

class UsersEActivitiesController extends BaseController {

	const KEY_EACTIVITIES_SESSION = 'eactivities_session';

	/**
	 * Display eActivities sign in form.
	 *
	 * @return Response
	 */
	public function getSignIn()
	{
		return View::make('admin.users.eactivities.sign_in');
	}

	/**
	 * Validate eActivities sign in credentials.
	 *
	 * @return Response
	 */
	public function postSignIn()
	{
		$inputs = array(
			'password' => Input::get('password'),
		);

		$rules = array(
			'password' => 'required',
		);

		$validator = Validator::make($inputs, $rules);

		if ($validator->passes()) {
			$client = $this->createEActivitiesClient();
			if ($client->signIn(Auth::user()->username, $inputs['password'])) {
				Session::put(self::KEY_EACTIVITIES_SESSION, $client->getSessionId());
				return Redirect::action('Admin\UsersEActivitiesController@getRoles')
					->with('success', 'You have successfully signed in to eActivities');
			} else {
				return Redirect::action('Admin\UsersEActivitiesController@getSignIn')
					->with('danger', 'Invalid password');
			}
		} else {
			return Redirect::action('Admin\UsersEactivitiesController@getSignIn')
				->withErrors($validator);
		}
	}

	/**
	 * Display signed in user's club and society roles.
	 *
	 * @return Response
	 */
	public function getRoles()
	{
		$client = $this->createEActivitiesClient();
		if ( ! $client->isSignedIn()) {
			return $this->createSignInAgainRedirection();
		}

		$roles = $client->getCurrentAndOtherRoles();

		return View::make('admin.users.eactivities.roles')
			->with('current_role', $roles['current'])
			->with('other_roles', $roles['others']);
	}

	/**
	 * Select a club and society role.
	 *
	 * @return Response
	 */
	public function putSelectRole()
	{
		$client = $this->createEActivitiesClient();
		if ( ! $client->isSignedIn()) {
			return $this->createSignInAgainRedirection();
		}

		$role_id = (int) Input::get('role_id');
		$roles = $client->getCurrentAndOtherRoles();

		if (isset($roles['others'][$role_id])) {
			$client->changeRole($role_id);
			$roles = $client->getCurrentAndOtherRoles();
			return Redirect::action('Admin\UsersEActivitiesController@getRoles')
				->with('success', 'Successfully changed role and society to '.$roles['current']);
		} else {
			return Redirect::action('Admin\UsersEActivitiesController@getRoles')
				->with('danger', 'Cannot change role');
		}
	}

	/**
	 * Perform members list synchronization.
	 *
	 * @return Response
	 */
	public function postPerform()
	{
		$client = $this->createEActivitiesClient();
		if ( ! $client->isSignedIn()) {
			return $this->createSignInAgainRedirection();
		}

		$members = $client->getMembersList();

		// Reset membership status
		User::resetMemberships();

		foreach ($members as $member) {
			// Find or create
			$user = User::where('username', '=', $member['login'])->first();
			if ( ! $user) {
				$user = new User;
				$user->username = $member['login'];
			}

			$user->name      = "{$member['first_name']} {$member['last_name']}";
			$user->email     = $member['email'];
			$user->cid       = $member['cid'];
			$user->is_member = true;
			$user->save();
		}

		Session::forget(self::KEY_EACTIVITIES_SESSION);

		return Redirect::route('admin.users.index')
			->with('success', 'Successfully synchronized users from eActivities');
	}

	/**
	 * Creates an eActivities API client.
	 *
	 * @return EActivitiesClient
	 */
	private function createEActivitiesClient()
	{
		$http_client = new HttpClient;

		if (App::environment() === 'local') {
			$http_client->setSslVerification(false);
		}

		$eactivities_client = new EActivitiesClient($http_client);

		if (Session::has(self::KEY_EACTIVITIES_SESSION)) {
			$eactivities_client->setSessionId(Session::get(self::KEY_EACTIVITIES_SESSION));
		}

		return $eactivities_client;
	}

	/**
	 * Redirect response if user's eActivities session expired.
	 *
	 * @return Response
	 */
	private function createSignInAgainRedirection()
	{
		return Redirect::action('Admin\UsersEActivitiesController@getSignIn')
			->with('danger', 'Session expired. Please sign in again');
	}

}