<?php

class InstagramPhoto extends Eloquent {

	/*
	Relations
	 */

	public function user()
	{
		return $this->belongsTo('User');
	}

	public function setTagsAttribute(array $tags)
	{
		$this->attributes['tags'] = json_encode($tags);
	}

	public function getTagsAttribute()
	{
		return json_decode($this->attributes['tags']);
	}

	/*
	Scopes
	 */

	public function scopeLatest($query)
	{
		return $query->orderBy('captured_time', 'desc');
	}

	/**
	 * Refresh an Instagram photo's information
	 * @param  array $photo
	 */
	public static function refresh($data)
	{
		$photo = static::find($data['id']);
		if ( ! $photo) {
			$photo = new static;
			$photo->id = $data['id'];
		}

		$photo->tags                          = $data['tags'];
		$photo->latitude                      = array_get($data, 'location.latitude');
		$photo->longitude                     = array_get($data, 'location.longitude');
		$photo->captured_time                 = $data['created_time'];
		$photo->link                          = $data['link'];
		$photo->image_low_resolution_url      = array_get($data, 'images.low_resolution.url');
		$photo->image_thumbnail_url           = array_get($data, 'images.thumbnail.url');
		$photo->image_standard_resolution_url = array_get($data, 'images.standard_resolution.url');
		$photo->instagram_username            = array_get($data, 'user.username');
		$photo->instagram_user_id             = array_get($data, 'user.id');
		$photo->type                          = $data['type'];

		$photo->save();
	}

}