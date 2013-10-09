<?php

class EventsController extends BaseController {

	public function getIndex()
	{
		return View::make('events.index')
			->with('events', EventDay::all());
	}

}