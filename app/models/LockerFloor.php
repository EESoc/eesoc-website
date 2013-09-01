<?php

class LockerFloor extends Eloquent {

	public $timestamps = false;

	public function lockerClusters()
	{
		return $this->hasMany('LockerCluster');
	}

}