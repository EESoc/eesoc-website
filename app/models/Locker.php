<?php

use Robbo\Presenter\PresentableInterface;

class Locker extends Eloquent implements PresentableInterface {

    const STATUS_VACANT = 'vacant';
    const STATUS_RESERVED = 'reserved';
    const STATUS_TAKEN = 'taken';
	const STATUS_TRANSITION = 'transition';
	
    const AUDIT_LOCKED = 'locked';
    const AUDIT_GOOD = 'available';
    const AUDIT_BROKEN = 'broken';

    /*
    Relations
     */

    public function lockerCluster()
    {
        return $this->belongsTo('LockerCluster');
    }

    public function owner()
    {
        return $this->belongsTo('User');
    }

    /*
    Scopes
     */

    public function scopeOwnedBy($query, User $user)
    {
        return $query->where('owner_id', '=', $user->id);
    }

    /*
    Attribute getters
     */

    public function getIsVacantAttribute()
    {
        return ($this->status === self::STATUS_VACANT);
    }

    public function getIsReservedAttribute()
    {
        return ($this->status === self::STATUS_RESERVED || $this->status === self::STATUS_TRANSITION );
    }
	
    public function getIsTransitionAttribute()
    {
        return ($this->status === self::STATUS_TRANSITION);
    }

    public function getIsTakenAttribute()
    {
        return ($this->status === self::STATUS_TAKEN);
    }	
	
    public function getIsAvailbleAttribute()
    {
		
		return (( $this->audit == self::AUDIT_GOOD  && $this->is_vacant ) || ( $this->audit == self::AUDIT_LOCKED && $this->is_transition ));
    }

    /**
     * Can Locker be claimed by this user.
     * @param  User   $user
     * @return boolean
     */
    public function canBeClaimedBy(User $user)
    {
        return (($this->is_vacant || ($this->is_transition && $this->owner_id === $user->id)) && $user->unclaimed_lockers_count > 0);
    }

    /**
     * Check if Locker is owned by this user.
     * @param  User   $user
     * @return boolean
     */
    public function isOwnedBy(User $user)
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
