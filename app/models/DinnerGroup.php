<?php

class DinnerGroup extends Eloquent {

    //Max size reduced for BAE delegate at the table
    //const MAX_SIZE_REDUCED = 7;
    const MAX_NO_OF_GROUPS = 17;
    const MAX_SIZE_FULL    = 10;
    //const REDUCED_COUNT    = 9;

    const CAN_LEAVE_OWN_GRP = false; //only change this temporarily otherwise don't touch this!

    // 'Final year' group IDs from the live database (sorry...)
    //protected static $finalYearGroups = [3, 4, 5, 7, 8, 11, 12, 13, 14];

    /*
    Relations
     */

    public function owner()
    {
        return $this->belongsTo('User', 'owner_id');
    }

    public function members()
    {
        return $this->hasMany('DinnerGroupMember');
    }

    public function users()
    {
        return $this->belongsToMany('User', 'dinner_group_members', 'dinner_group_id', 'user_id');
    }

    public function addMember($user, User $actor = NULL)
    {
        $actor  = $actor ? $actor : Auth::user();
        $member = new DinnerGroupMember;

        //$existingMembers = $this->members()->count();
        $existingMembers = DinnerGroupMember::where('dinner_group_id','=',$this->id)->count();

        $member->DinnerGroup()->associate($this);

        if ($user instanceOf User)
        {
            $member->user()->associate($user);
            $member->is_owner = ($user->id === $this->owner->id);

            //If the group was left empty, then make the first member (again) the owner.
            if ($existingMembers == 0){
                $this->owner->id = $user->id;
                $member->is_owner = true;
            }
        }
        else
        {
            $member->name = $user;
        }

        $member->addedByUser()->associate($actor);
        $member->ticketPurchaser()->associate($actor);
        $member->save();
    }

    public function removeMember(User $user)
    {
        $member = $this->members()->where('user_id', '=', $user->id)->first();
        $membersInGroup = DinnerGroupMember::where('dinner_group_id','=',$this->id)->count();

        if ($member->is_owner  && !DinnerGroup::CAN_LEAVE_OWN_GRP)
        {
            return Redirect::route('dashboard.dinner.groups.show', $member->dinner_group_id)
                ->with('danger', 'You cannot leave from your own group..');
        }

        $member->delete();

        if ($membersInGroup <= 1){
            $this->delete();

            return Redirect::route('dashboard.dinner.groups.index')
                ->with('success', 'You have left the group.');
        }
				
		$this->is_full = (($membersInGroup - 1) >= $this->max_size);
        
		$this->save();	

        return true;
    }

    public function isFull()
    {
        return ($this->members->count() >= $this->max_size);
    }

    public function emptyCount()
    {
        return ($this->max_size - $this->members->count());
    }

    public function scopeHasLimit($query, $limit)
    {
        return $query->where('max_size', '=', $limit);
    }

    public static function boot()
    {
        parent::boot();

        // Auto create membership for group owners
        static::created(function($group) {
            $owner = $group->owner;
            $group->users()->save($owner, [
                'ticket_purchaser_id' => $owner->id,
                'is_owner' => true,
            ]);
        });
    }

    public static function createWithOwner(User $owner)
    {
        $group = new Self;
        $group->owner()->associate($owner);
        $group->max_size = Self::maxSizeByOwner($owner);

        $group->save();

        return $group;
    }

    public static function maxSizeByOwner(User $owner)
    {
        //$candidate = $owner->inGroup(self::$finalYearGroups, FALSE);

        //if ($candidate &&
            //DinnerGroup::hasLimit(self::MAX_SIZE_REDUCED)->count() < self::REDUCED_COUNT)
        //{
            //return self::MAX_SIZE_REDUCED;
        //}

        return self::MAX_SIZE_FULL;
    }
}
