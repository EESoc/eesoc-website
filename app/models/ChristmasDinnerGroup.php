<?php

class ChristmasDinnerGroup extends Eloquent {

	/*
	Relations
	 */

	public function owner()
	{
		return $this->belongsTo('User', 'owner_id');
	}

	public function members()
	{
		return $this->hasMany('ChristmasDinnerGroupMember');
	}

	public function users()
	{
		return $this->belongsToMany('User', 'christmas_dinner_group_members', 'christmas_dinner_group_id', 'user_id');
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

}