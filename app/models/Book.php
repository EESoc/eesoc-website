<?php

use Robbo\Presenter\PresentableInterface;

class Book extends Eloquent implements PresentableInterface {

	protected $fillable = ['google_book_id', 'thumbnail', 'isbn', 'name', 'condition', 'target_student_groups', 'target_course', 'price', 'quantity', 'contact_instructions', 'expires_at'];

	protected $softDelete = true;

	public function user()
	{
		return $this->belongsTo('User');
	}

	public function scopeOwnedBy($query, User $user)
	{
		return $query->where('user_id', '=', $user->id);
	}

	public function scopeNotOwnedBy($query, User $user)
	{
		return $query->where('user_id', '<>', $user->id);
	}

	public function setPriceAttribute($price_in_decimal)
	{
		$this->price_in_pence = $price_in_decimal * 100;
	}

	public function getPriceAttribute()
	{
		return $this->price_in_pence / 100;
	}

	/**
	 * Return a created presenter.
	 *
	 * @return Robbo\Presenter\Presenter
	 */
	public function getPresenter()
	{
		return new BookPresenter($this);
	}

}