<?php

class EventDay extends Eloquent {

	protected $table = 'events';

	protected $fillable = ['name', 'date', 'starts_at', 'ends_at', 'location', 'description'];

	public function setDateAttribute($value)
	{
		$this->attributes['date'] = empty($value) ? null : $value;
	}

	public function setStartsAtAttribute($value)
	{
		$this->attributes['starts_at'] = empty($value) ? null : $value;
	}

	public function setEndsAtAttribute($value)
	{
		$this->attributes['ends_at'] = empty($value) ? null : $value;
	}

}