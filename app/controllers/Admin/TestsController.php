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

	public function getSendLockerReminder()
	{
		Notification::sendLockerClaimReminder(Auth::user());
		return 'OK';
	}

	public function getSendLockerTermsAndConditions()
	{
		Notification::sendLockerTermsAndConditions(Auth::user());
		return 'OK';
	}

}