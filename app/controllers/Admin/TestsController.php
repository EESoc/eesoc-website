<?php
namespace Admin;

use \Auth;
use \Notification;
use \Response;
use \User;

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

	public function getDinnerNonAttendees()
	{
		function array2csv(array &$array)
		{
			if (count($array) == 0) {
				return null;
			}
			ob_start();
			$df = fopen("php://output", 'w');
			fputcsv($df, array_keys(reset($array)));
			foreach ($array as $row) {
				fputcsv($df, $row);
			}
			fclose($df);
			return ob_get_clean();
		}

		$users = User::whereNotIn('id', function($query) {
			return $query->select('user_id')->from('dinner_sales');
		})->get();

		$csv_data = [];

		foreach ($users as $user) {
			$csv_data[] = [
				'name' => $user->name,
				'email' => $user->email,
			];
		}

		$response = Response::make(array2csv($csv_data), 200);
		$response->header('Content-Type', 'text/csv');
		$response->header('Content-disposition', 'attachment;filename=dinner_non_attendees.csv');

		return $response;
	}
}
