<?php

class Page extends Eloquent {

    const TYPE_DATABASE = 'database';
    const TYPE_ACTION = 'action';
    const TYPE_VIEW = 'view';

    protected $default_layout = 'layouts.application';
    protected $default_section = 'content';

    public static $PAGE_TYPES = [self::TYPE_DATABASE, self::TYPE_ACTION, self::TYPE_VIEW];

    protected $fillable = ['name', 'slug', 'type', 'action', 'layout', 'section', 'content'];

    /**
     * Return layout attribute.
     *
     * @return string
     */
    public function getLayoutAttribute()
    {
        return empty($this->layout) ? $this->default_layout : $this->layout;
    }

    /**
     * Return section attribute.
     *
     * @return string
     */
    public function getSectionAttribute()
    {
        return empty($this->section) ? $this->default_section : $this->section;
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
