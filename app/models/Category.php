<?php

class Category extends Eloquent {

	const ID_UNCATEGORISED = 1;

	protected $fillable = array('name', 'slug', 'description');

	public $timestamps = false;

	public static function boot()
	{
		parent::boot();

		Category::creating(function($category) {
			$category->position = Category::max('position') + 1;
		});

		// Category::saving(function($category) {
		// 	if (empty($category->slug)) {
		// 		$num = 0;
		// 		$slug = Str::slug($category->name);

		// 		do {
		// 			$category->slug = $slug;
		// 			if ($num > 0) {
		// 				$category->slug .= "-{$num}";
		// 			}

		// 			$num++;
		// 		} while (Category::where('id', '<>', $category->id)->where('slug', '=', $category->slug)->first());
		// 	}
		// });

	}

	/**
	 * Get `uncategorised` category
	 * @return Category
	 */
	public static function uncategorised()
	{
		return static::find(static::ID_UNCATEGORISED);
	}

	/*
	Scopes
	 */

	public function scopeAlphabetically($query)
	{
		return $query->orderBy('name');
	}

	/**
	 * Return is deletable attribute.
	 * 
	 * @return boolean
	 */
	public function getIsDeletableAttribute()
	{
		return ((int) $this->id !== static::ID_UNCATEGORISED);
	}

}