<?php

class AdminController extends BaseController {

	public function __construct()
	{
		$this->beforeFilter('auth.admin');
		$this->beforeFilter('csrf', array('on' => array('post', 'put', 'delete')));
	}

	public function getDashboard()
	{
		return View::make('admin.dashboard');
	}

}