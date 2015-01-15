<?php

use Robbo\Presenter\PresentableInterface;

class Content extends Eloquent implements PresentableInterface {

    protected $fillable = array('name', 'slug', 'content');

    /*
    Scopes
     */

    public function scopeAlphabetically($query)
    {
        return $query->orderBy('name');
    }

    /**
     * Fetch content by slug.
     * @param  string $slug
     * @return Content
     */
    public static function fetch($slug)
    {
        $content = static::where('slug', '=', $slug)->first();
        if ( ! $content) {
            $content = static::createNotFound($slug);
        }

        return $content;
    }

    public static function createNotFound($slug)
    {
        $content = new static;
        $content->content = sprintf('<p style="background-color: red; color: white; padding: 10px">Content \'%s\' has yet to be created.</p>', $slug);

        return $content;
    }

    /**
     * Return is deletable attribute.
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
     * @return Robbo\Presenter\Presenter
    */
    public function getPresenter()
    {
        return new ContentPresenter($this);
    }

}
