<?php

class Page extends Eloquent {

	public static $DEFAULT_LAYOUT  = 'layouts.application';
	public static $DEFAULT_SECTION = 'content';

	protected $fillable = array('name', 'slug', 'type', 'action', 'layout', 'section', 'content');

	/**
	 * Return layout attribute.
	 * 
	 * @return string
	 */
	public function getLayoutAttribute()
	{
		return empty($this->layout) ? static::$DEFAULT_LAYOUT : $this->layout;
	}

	/**
	 * Return section attribute.
	 * 
	 * @return string
	 */
	public function getSectionAttribute()
	{
		return empty($this->section) ? static::$DEFAULT_SECTION : $this->section;
	}

	/**
	 * Return is deletable attribute.
	 * Cannot delete root page.
	 * 
	 * @return boolean
	 */
	public function getIsDeletableAttribute()
	{
		return ( ! empty($this->slug));
	}

}