<?php

class SessionsController extends BaseController {

    public function __construct()
    {
        parent::__construct();

        $this->beforeFilter('auth', array('only' => array('deleteDestroy')));
        $this->beforeFilter('guest', array('except' => array('deleteDestroy')));
    }

    /**
     * Display sign in from.
     *
     * @return Response
     */
    public function getNew()
    {
        return View::make('sessions.new');
    }

    /**
     * Validate credentials and sign in user.
     *
     * @return Response
     */
    public function postCreate()
    {
        $inputs = array(
            'username' => strtolower(Input::get('username')),
            'password' => Input::get('password'),
        );

        $rules = array(
            'username' => 'required',
            'password' => 'required',
        );

        $validator = Validator::make($inputs, $rules);

        if ($validator->passes()) {
            if (Auth::attempt($inputs, Input::get('remember_me') === 'true')) {
                Auth::user()->recordSignIn();
                $defaultURL = URL::action('UsersController@getDashboard');
                return Redirect::intended($defaultURL);
            } else {
                return Redirect::action('SessionsController@getNew')
                    ->withInput()
                    ->with('danger', 'Invalid username and/or password');
            }
        } else {
            return Redirect::action('SessionsController@getNew')
                ->withInput()
                ->withErrors($validator);
        }
    }

    /**
     * Sign out action.
     *
     * @return Response
     */
    public function deleteDestroy()
    {
        Auth::logout();
        return Redirect::to('/')
            ->with('success', 'You have successfully signed out');
    }

}
