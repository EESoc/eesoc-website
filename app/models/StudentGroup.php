<?php

class StudentGroup extends Eloquent {

	public function users()
	{
		return $this->hasMany('User');
	}

	public function parent()
	{
		return $this->belongsTo('StudentGroup', 'parent_id');
	}

	public function children()
	{
		return $this->hasMany('StudentGroup', 'parent_id');
	}

	public function scopeOfficial($query)
	{
		return $query->where('is_official', '=', true);
	}

	public function scopeRoot($query)
	{
		return $query->whereNull('parent_id');
	}

	public function scopeAlphabetically($query)
	{
		return $query->orderBy('name');
	}

	public function getRelatedGroupIdsAttribute()
	{
		$group_ids = array();
		$group_ids[] = $this->id;

		foreach ($this->children()->get() as $child) {
			$group_ids[] = $child->id;
			$group_ids = array_merge($group_ids, $child->related_group_ids);
		}

		return $group_ids;
	}

}