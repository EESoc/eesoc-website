<?php
namespace Admin;

use \Auth;
use \Notification;

class TestsController extends BaseController {

	public function getSendLockerNotification()
	{
		Notification::sendLockerInformation(Auth::user());
		return 'OK';
	}

}