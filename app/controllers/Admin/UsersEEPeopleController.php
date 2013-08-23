<?php
namespace Admin;

use App;
use Auth;
use EEPeople\Synchronizer as EEPeopleSynchronizer;
use EEPeople\Client as EEPeopleClient;
use Guzzle\Http\Client as HttpClient;
use ImperialCollegeLogin;
use Input;
use Validator;
use View;

class UsersEEPeopleController extends BaseController {

	/**
	 * Display EEPeople sign in form.
	 *
	 * @return Response
	 */
	public function getSignIn()
	{
		return View::make('admin.users.eepeople.sign_in');
	}

	/**
	 * Validate EEPeople sign in credentials.
	 *
	 * @return Response
	 */
	public function postSignInAndPerform()
	{		$inputs = array(
			'password' => Input::get('password'),
		);

		$rules = array(
			'password' => 'required',
		);

		$validator = Validator::make($inputs, $rules);

		if ($validator->passes()) {
			$client = $this->createEEPeopleClient();
			$credentials = new ImperialCollegeLogin(Auth::user()->username, $inputs['password']);

			if ($client->signIn($credentials)) {
				$synchronizer = new EEPeopleSynchronizer($client);
				$synchronizer->perform();
				return Redirect::route('admin.users.index')
					->with('success', 'Successfully synchronized users from EEPeople');
			} else {
				return Redirect::action('Admin\UsersEEPeopleController@getSignIn')
					->with('danger', 'Invalid password');
			}
		} else {
			return Redirect::action('Admin\UsersEEPeopleController@getSignIn')
				->withErrors($validator);
		}
	}

	/**
	 * Creates an EEPeople API client.
	 *
	 * @return EEPeopleClient
	 */
	private function createEEPeopleClient()
	{
		$http_client = new HttpClient;

		if (App::environment() === 'local') {
			$http_client->setSslVerification(false);
		}

		return new EEPeopleClient($http_client);
	}

}