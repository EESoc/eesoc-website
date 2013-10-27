<?php

class EventDay extends Eloquent {

	protected $table = 'events';

	protected $fillable = ['name', 'date', 'starts_at', 'ends_at', 'location', 'description', 'category_id'];

	public function __construct(array $attributes = array())
	{
		// Set default category
		$attributes['category_id'] = Category::uncategorised()->id;

		parent::__construct($attributes);
	}

	/*
	Relations
	 */

	public function category()
	{
		return $this->belongsTo('Category');
	}

	/*
	Scopes
	 */

	public function scopeHasDate($query)
	{
		return $query->whereNotNull('date');
	}

	public function scopeVisible($query)
	{
		return $query->where('hidden', '=', false);
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