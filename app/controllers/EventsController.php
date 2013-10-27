<?php

class EventsController extends BaseController {

	public function getIndex()
	{
		$events = EventDay::orderBy('date')
			->hasDate()
			->get();

		return View::make('events.index')
			->with('events', $events);
	}

}