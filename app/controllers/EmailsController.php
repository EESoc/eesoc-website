<?php

class EmailsController extends BaseController {

	public function getTrack($tracker_token)
	{
		$queue = NewsletterEmailQueue::where('tracker_token', '=', $tracker_token)->firstOrFail();
		$queue->views++;
		$queue->save();

		$pixel = "\x47\x49\x46\x38\x37\x61\x1\x0\x1\x0\x80\x0\x0\xfc\x6a\x6c\x0\x0\x0\x2c\x0\x0\x0\x0\x1\x0\x1\x0\x0\x2\x2\x44\x1\x0\x3b";
		$response = Response::make($pixel, 200);
		$response->header('Content-Type', 'image/gif');

		return $response;
	}

}