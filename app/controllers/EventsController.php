<?php

class EventsController extends BaseController {

	public function getIndex()
	{
		$events = EventDay::visible()
			->hasDate()
			->orderBy('date')
			->get();

		return View::make('events.index')
			->with('events', $events);
	}

}