<?php

use Robbo\Presenter\PresentableInterface;

class Content extends Eloquent implements PresentableInterface {

	protected $fillable = array('name', 'slug', 'content');

	public static function fetch($slug)
	{
		return static::where('slug', '=', $slug)->first();
	}

	public function getIsDeletableAttribute()
	{
		return true;
	}

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