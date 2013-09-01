<?php

use Robbo\Presenter\PresentableInterface;

class Locker extends Eloquent implements PresentableInterface {

	const STATUS_VACANT = 'vacant';
	const STATUS_RESERVED = 'reserved';
	const STATUS_TAKEN = 'taken';

	public function lockerCluster()
	{
		return $this->belongsTo('LockerCluster');
	}

	public function owner()
	{
		return $this->belongsTo('User');
	}

	public function getIsVacantAttribute()
	{
		return ($this->status === self::STATUS_VACANT);
	}

	public function getIsReservedAttribute()
	{
		return ($this->status === self::STATUS_RESERVED);
	}

	public function getIsTakenAttribute()
	{
		return ($this->status === self::STATUS_TAKEN);
	}

	public function newCollection(array $models = array())
	{
		return new LockerCollection($models);
	}

	/**
	 * Return a created presenter.
	 *
	 * @return Robbo\Presenter\Presenter
	 */
	public function getPresenter()
	{
		return new LockerPresenter($this);
	}

}