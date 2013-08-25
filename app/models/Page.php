<?php

class Page extends Eloquent {

	public static $DEFAULT_LAYOUT  = 'layouts.application';
	public static $DEFAULT_SECTION = 'content';

	protected $fillable = array('name', 'slug', 'type', 'action', 'layout', 'section', 'content');

	public function getLayoutAttribute()
	{
		return empty($this->layout) ? static::$DEFAULT_LAYOUT : $this->layout;
	}

	public function getSectionAttribute()
	{
		return empty($this->section) ? static::$DEFAULT_SECTION : $this->section;
	}

	public function getIsDeletableAttribute()
	{
		// Cannot delete home page
		return ( ! empty($this->slug));
	}

}