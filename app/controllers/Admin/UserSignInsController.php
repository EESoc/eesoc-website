<?php
namespace Admin;

use \Input;
use \UserSignIn;
use \View;

class UserSignInsController extends BaseController {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $query = UserSignIn::with('user')
            ->orderBy('created_at', 'desc');

        if ($user_id = Input::get('user_id')) {
            $query->where('user_id', '=', $user_id);
        }

        $user_sign_ins = $query->get();
        return View::make('admin.user_sign_ins.index')
            ->with('user_sign_ins', $user_sign_ins);
    }

}
