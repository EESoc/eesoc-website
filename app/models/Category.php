<?php

class Category extends Eloquent {

	protected $fillable = array('name', 'slug', 'description');

	public $timestamps = false;

	public static function boot()
	{
		parent::boot();

		Category::creating(function($category) {
			$category->position = Category::max('position') + 1;
		});

		Category::saving(function($category) {
			if (empty($category->slug)) {
				$num = 0;
				$slug = Str::slug($category->name);

				do {
					$category->slug = $slug;
					if ($num > 0) {
						$category->slug .= "-{$num}";
					}

					$num++;
				} while (Category::where('id', '<>', $category->id)->where('slug', '=', $category->slug)->first());
			}
		});

	}

	public function isDeletable()
	{
		return ($this->id !== 1);
	}

}