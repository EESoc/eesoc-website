<?php

class AdminUsersSynchronizeController extends AdminController {

	const KEY_COOKIEJAR = 'eactivities_cookie_jar';

	public function getSignIn()
	{
		return View::make('admin.users.synchronize.sign_in');
	}

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
				Session::put(static::KEY_COOKIEJAR, $client->getCookies());
				return Redirect::action('AdminUsersSynchronizeController@getRoles');
			} else {
				return Redirect::action('AdminUsersSynchronizeController@getSignIn')->with('danger', 'Invalid password');
			}
		} else {
			return Redirect::action('AdminUsersSynchronizeController@getSignIn')->withErrors($validator);
		}
	}

	public function getRoles()
	{
		$client = $this->createEActivitiesClient();
		if ( ! $client->isSignedIn()) {
			return $this->createSignInAgainRedirection();
		}
	}

	private function createEActivitiesClient()
	{
		$client = new EActivitiesClient(new Zend\Http\Client);

		if (Session::has(static::KEY_COOKIEJAR)) {
			$client->setCookies(Session::get(static::KEY_COOKIEJAR));
		}

		return $client;
	}

	private function createSignInAgainRedirection()
	{
		return Redirect::action('AdminUsersSynchronizeController@getSignIn')->with('danger', 'Session expired. Please sign in again');
	}

}