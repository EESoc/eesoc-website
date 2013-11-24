<?php

class ChristmasDinnerGroupMember extends Eloquent {

	/*
	Relations
	 */

	public function christmasDinnerGroup()
	{
		return $this->belongsTo('christmasDinnerGroup');
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

}