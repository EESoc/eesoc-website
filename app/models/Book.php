<?php

class Book extends Eloquent {

	protected $fillable = ['google_book_id', 'isbn', 'name', 'condition', 'target_student_groups', 'target_course', 'price', 'quantity', 'contact_instructions', 'expires_at'];

	protected $softDelete = true;

	public function user()
	{
		return $this->belongsTo('User');
	}

	public function setPriceAttribute($price_in_decimal)
	{
		$this->price_in_pence = $price_in_decimal * 100;
	}

	public function getPriceAttribute()
	{
		return $this->price_in_pence / 100;
	}

}