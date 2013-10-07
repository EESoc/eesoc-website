<?php

class Post extends Eloquent {

	protected $fillable = ['category_id', 'name', 'slug', 'is_visible', 'content', 'published_at'];

}