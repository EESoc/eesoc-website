<?php

class DinnerGroupMember extends Eloquent
{
	public $timestamps = false;

	public static function boot()
	{
		parent::boot();

		// Auto create membership for group owners
		static::created(function($member) {
			$group = $member->dinner_group;

			if ($group->members->count() >= 10) {
				$group->is_full = true;
				$group->save();
			}
		});
	}

	/*
	Relations
	 */

	public function dinnerGroup()
	{
		return $this->belongsTo('dinnerGroup');
	}

	public function user()
	{
		return $this->belongsTo('User');
	}

	public function addedByUser()
	{
		return $this->belongsTo('User', 'added_by_user_id');
	}

	public function ticketPurchaser()
	{
		return $this->belongsTo('User', 'ticket_purchaser_id');
	}

	/*
	Scopes
	 */
	public function scopePurchasedBy($query, User $user)
	{
		return $query->where('ticket_purchaser_id', '=', $user->id);
	}

	public function getNameAttribute()
	{
		if ($this->attributes['name']) {
			return $this->attributes['name'];
		} else if ($this->user) {
			return $this->user->name;
		}
	}
}
