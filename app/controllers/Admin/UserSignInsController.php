<?php
namespace Admin;

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
		$user_sign_ins = UserSignIn::with('user')->orderBy('created_at', 'desc')->get();
		return View::make('admin.user_sign_ins.index')
			->with('user_sign_ins', $user_sign_ins);
	}

}