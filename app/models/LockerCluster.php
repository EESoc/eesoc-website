<?php

class LockerCluster extends Eloquent {

	public $timestamps = false;

	public function lockerFloor()
	{
		return $this->belongsTo('LockerFloor');
	}

	public function lockers()
	{
		return $this->hasMany('Locker');
	}

}