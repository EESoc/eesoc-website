<?php
namespace Admin;

use \Auth;
use \Input;
use \Redirect;
use \Response;
use \Str;
use \StudentGroup;
use \User;
use \View;

class UsersController extends BaseController {

    /**
     * Number of users to display per page
     * @var integer
     */
    protected static $USERS_PER_PAGE = 20;

    /**
     * Display a list of users. Able to apply filters and search query.
     *
     * @return Response
     */
    public function index()
    {
        $users = User::with('studentGroup')->adminsFirst();

        $request_params = array(
            'filter'   => Input::get('filter'),
            'query'    => Input::get('query'),
            'group_id' => Input::get('group_id'),
        );

        /**
         * Filtering by scope
         */
        if ( ! empty($request_params['filter'])) {
            if (in_array($request_params['filter'], ['admin', 'non-admin', 'member', 'non-member'])) {
                $users->{Str::camel($request_params['filter'])}();
            } else {
                $request_params['filter'] = null;
            }
        }

        /**
         * Searching
         */
        if ( ! empty($request_params['query'])) {
            $users->searching($request_params['query']);
        }

        /**
         * By year group
         */
        if ( ! empty($request_params['group_id'])) {
            $selected_group = StudentGroup::findOrFail($request_params['group_id']);
            $users->inGroup($selected_group);
        } else {
            $selected_group = null;
        }

        return View::make('admin.users.index')
            ->with('users', $users->paginate(self::$USERS_PER_PAGE))
            ->with(User::statistics())
            ->with('request_params', $request_params)
            ->with('groups', StudentGroup::with('children')->root()->alphabetically()->get())
            ->with('selected_group', $selected_group);
    }

    public function getImage($username)
    {
        $user = User::where('username', '=', $username)->firstOrFail();
        return Response::make($user->image_blob, 200, array(
            'Content-Type' => $user->image_content_type,
        ));
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

    /**
     * Display a list of users' photo
     *
     * @return Response
     */
    public function getWall()
    {
        return View::make('admin.users.wall')
            ->with('users', User::adminsFirst()->hasImage()->get());
    }

    /**
     * Pretends to be a user
     *
     * @return Response
     */
    public function getPretend($username)
    {
        $user = User::where('username', '=', $username)->firstOrFail();

        Auth::logout();

        Auth::login($user);

        return Redirect::to('/')
            ->with('success', "Successfully signed in as {$user->username}");
    }

}
