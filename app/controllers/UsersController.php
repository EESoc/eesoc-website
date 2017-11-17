<?php

use Newsletter;
use Input;
use Redirect;
use View;

class UsersController extends BaseController {

    public function getIndex(){
        return $this->getDashboard();
    }

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
            ->with('user', Auth::user())
            ->with('newsletters', Newsletter::all());
    }

    public function postSubscription($username)
    {
        $user = User::where('username', '=', $username)->firstOrFail();

        $user->newsletters()->sync((array) Input::get('newsletter_ids'));
        return Redirect::action('UsersController@getIndex')
        ->with('success', 'Newsletter subscription has been successfully updated');
        
        //return Redirect::route('admin.emails.edit', $email->id)
                //->withInput()
                //->withErrors($validator);
    }

}
