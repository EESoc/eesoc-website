<?php

class SponsorsController extends BaseController {

	public function getIndex()
	{
		return View::make('sponsors.index')
			->with('sponsors', Sponsor::all());
	}

}