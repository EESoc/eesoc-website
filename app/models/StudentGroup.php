<?php

class StudentGroup extends Eloquent {

	public function users()
	{
		return $this->hasMany('User');
	}

	public function scopeOfficial($query)
	{
		return $query->where('is_official', '=', true);
	}

}