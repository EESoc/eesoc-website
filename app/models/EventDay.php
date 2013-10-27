<?php

class EventDay extends Eloquent {

	protected $table = 'events';

	protected $fillable = ['name', 'date', 'starts_at', 'ends_at', 'location', 'description'];

	public function scopeHasDate($query)
	{
		return $query->whereNotNull('date');
	}

	/**
	 * Forces input to be null if blank
	 * @param string $value
	 */
	public function setDateAttribute($value)
	{
		$this->attributes['date'] = empty($value) ? null : $value;
	}

	/**
	 * Forces input to be null if blank
	 * @param string $value
	 */
	public function setStartsAtAttribute($value)
	{
		$this->attributes['starts_at'] = empty($value) ? null : $value;
	}

	/**
	 * Forces input to be null if blank
	 * @param string $value
	 */
	public function setEndsAtAttribute($value)
	{
		$this->attributes['ends_at'] = empty($value) ? null : $value;
	}

	public function getDatetimeObjectAttribute()
	{
		if ($this->date) {
			return DateTime::createFromFormat('Y-m-d', $this->date);
		} else {
			return null;
		}
	}

	public function getStartsAtDatetimeObjectAttribute()
	{
		if ($this->starts_at) {
			return DateTime::createFromFormat('H:i:s', $this->starts_at);
		} else {
			return null;
		}
	}

	public function getEndsAtDatetimeObjectAttribute()
	{
		if ($this->ends_at) {
			return DateTime::createFromFormat('H:i:s', $this->ends_at);
		} else {
			return null;
		}
	}

}