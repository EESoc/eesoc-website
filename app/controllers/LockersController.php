<?php

class LockersController extends BaseController {

	public function getIndex()
	{
		return View::make('lockers.index')
			->with('locker_floors', LockerFloor::sorted()->get());
	}

}