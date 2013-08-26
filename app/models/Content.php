<?php

use Robbo\Presenter\PresentableInterface;

class Content extends Eloquent implements PresentableInterface {

	protected $fillable = array('name', 'slug', 'content');

	public function scopeAlphabetically($query)
	{
		return $query->orderBy('name');
	}

	/**
	 * Fetch content by slug.
	 * 
	 * @param  string $slug
	 * @return Content
	 */
	public static function fetch($slug)
	{
		return static::where('slug', '=', $slug)->first();
	}

	/**
	 * Return is deletable attribute.
	 * 
	 * @return boolean
	 */
	public function getIsDeletableAttribute()
	{
		return true;
	}

	/**
	 * @return string
	 */
	public function __toString()
	{
		return $this->content;
	}

	/**
	 * Return a created presenter.
	 *
	 * @return Robbo\Presenter\Presenter
	*/
	public function getPresenter()
	{
		return new ContentPresenter($this);
	}

}