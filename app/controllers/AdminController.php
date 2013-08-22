<?php

class AdminController extends BaseController {

	public function __construct()
	{
		parent::__construct();
		
		$this->beforeFilter('auth.admin');
	}

	/**
	 * Display admin dashboard.
	 *
	 * @return Response
	 */
	public function getDashboard()
	{
		return View::make('admin.dashboard');
	}

}