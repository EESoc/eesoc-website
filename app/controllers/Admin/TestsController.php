<?php
namespace Admin;

class TestsController extends BaseController {

	public function getSendLockerNotification()
	{
		Notification::sendLockerInformation(Auth::user());
		return 'OK';
	}

}