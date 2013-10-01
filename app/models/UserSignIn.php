<?php

class UserSignIn extends Eloquent {

	/*
	Relations
	 */

	public function user()
	{
		return $this->belongsTo('User');
	}

}