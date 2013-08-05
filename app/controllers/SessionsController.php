<?php

class SessionsController extends BaseController {

	public function __construct()
	{
		$this->beforeFilter('auth', array('only' => array('deleteDestroy')));
		$this->beforeFilter('guest', array('except' => array('deleteDestroy')));
		$this->beforeFilter('csrf', array('on' => array('post', 'put', 'delete')));
	}

	public function getNew()
	{
		return View::make('sessions.new');
	}

	public function postCreate()
	{
		$inputs = array(
			'username' => strtolower(Input::get('username')),
			'password' => Input::get('password')
		);

		$rules = array(
			'username' => 'required',
			'password' => 'required'
		);

		$validator = Validator::make($inputs, $rules);

		if ($validator->passes()) {
			if (Auth::attempt($inputs)) {
				$userProfileURL = URL::action('UsersController@getShow', array('username' => $inputs['username']));
				return Redirect::intended($userProfileURL);
			} else {
				return Redirect::action('SessionsController@getNew')->withInput()->with('danger', 'Invalid username and/or password');
			}
		} else {
			return Redirect::action('SessionsController@getNew')->withInput()->withErrors($validator);
		}
	}

	public function deleteDestroy()
	{
		Auth::logout();
		return Redirect::to('/')->with('success', 'You have successfully signed out');
	}

}