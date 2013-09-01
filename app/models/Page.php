<?php

class Page extends Eloquent {

	protected $default_layout = 'layouts.application';
	protected $default_section = 'content';

	public static $PAGE_TYPES = array('database', 'action', 'view');

	protected $fillable = array('name', 'slug', 'type', 'action', 'layout', 'section', 'content');

	public function getLayoutAttribute()
	{
		return empty($this->layout) ? $this->default_layout : $this->layout;
	}

	public function getSectionAttribute()
	{
		return empty($this->section) ? $this->default_section : $this->section;
	}

	public function getIsDeletableAttribute()
	{
		// Cannot delete home page
		return ( ! empty($this->slug));
	}

}