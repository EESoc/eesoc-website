<?php

class Newsletter extends Eloquent {

	public function users()
	{
		return $this->belongsToMany('User', 'user_subscriptions');
	}

	public function emails()
	{
		return $this->hasMany('NewsletterEmail');
	}

}