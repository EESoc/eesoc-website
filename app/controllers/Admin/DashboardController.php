<?php
namespace Admin;

use \View;

class DashboardController extends BaseController {

	public function getShow()
	{
		return View::make('admin.dashboard');
	}

}