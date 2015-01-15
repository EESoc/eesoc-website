<?php
namespace Admin;

use \App;
use \Auth;
use \EActivities\Client as EActivitiesClient;
use \EActivities\Synchronizer as EActivitiesSynchronizer;
use \ImperialCollegeCredential;
use \Input;
use \Redirect;
use \Session;
use \User;
use \Validator;
use \View;

class UsersEActivitiesController extends BaseController {

    protected static $KEY_EACTIVITIES_SESSION = 'eactivities_session';

    private $client;

    /**
     * Decide where to start.
     *
     * @return Resonse
     */
    public function getBegin()
    {
        $this->client = $this->createEActivitiesClient();
        if ($this->client->isSignedIn()) {
            return $this->getRoles();
        } else {
            return $this->getSignIn();
        }
    }

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

            $credential = new ImperialCollegeCredential(Auth::user()->username, $inputs['password']);

            if ($client->signIn($credential)) {
                Session::put(self::$KEY_EACTIVITIES_SESSION, $client->getSessionId());
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
        $client = $this->getEActivitiesClientEnsureSignedIn();
        if ($client instanceof Response) {
            return $client;
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
        $client = $this->getEActivitiesClientEnsureSignedIn();
        if ($client instanceof Response) {
            return $client;
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
        $client = $this->getEActivitiesClientEnsureSignedIn();
        if ($client instanceof Response) {
            return $client;
        }

        (new EActivitiesSynchronizer($client))->perform();

        Session::forget(self::$KEY_EACTIVITIES_SESSION);

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

        $http_client->setSslVerification(false);

        $eactivities_client = new EActivitiesClient($http_client);

        if (Session::has(self::$KEY_EACTIVITIES_SESSION)) {
            $eactivities_client->setSessionId(Session::get(self::$KEY_EACTIVITIES_SESSION));
        }

        return $eactivities_client;
    }

    /**
     * Get existing eActivities client if already created, ensuring user is signed in.
     *
     * @return mixed
     */
    private function getEActivitiesClientEnsureSignedIn()
    {
        if ( ! $this->client) {
            $this->client = $this->createEActivitiesClient();
        }

        if ( ! $this->client->isSignedIn()) {
            return $this->createSessionExpiredResponse();
        } else {
            return $this->client;
        }
    }

    /**
     * Redirect response if user's eActivities session expired.
     *
     * @return Response
     */
    private function createSessionExpiredResponse()
    {
        return Redirect::action('Admin\UsersEActivitiesController@getSignIn')
            ->with('danger', 'Session expired. Please sign in again');
    }

}
