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

	/**
	 * Can Locker be claimed by this user.
	 * @param  User   $user
	 * @return boolean
	 */
	public function canBeClaimedBy(User $user)
	{
		// @TODO Check for unprocessed purchases
		return $this->is_vacant;
	}

	/**
	 * Check if Locker is owned by this user.
	 * @param  User   $user
	 * @return boolean
	 */
	public function ownedBy(User $user)
	{
		return ($this->owner_id === $user->id);
	}

	/**
	 * Claim this locker
	 * @param  User   $user
	 * @return [type]       [description]
	 */
	public function performClaimBy(User $user)
	{
		$this->owner()->associate($user);
		$this->status = self::STATUS_TAKEN;

		// @TODO mark user's sale as processed
		return $this->save();
	}

	/**
	 * New collection class
	 * @param  array  $models
	 * @return LockerCollection
	 */
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