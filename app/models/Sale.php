<?php

use Robbo\Presenter\PresentableInterface;

class Sale extends Eloquent implements PresentableInterface {

	public function user()
	{
		return $this->belongsTo('User');
	}

	public function scopeLocker($query)
	{
		// Sets the locker scope
		// @todo in the future: limit year?
		return $query->whereIn('product_name', ['Single Locker']);
	}

	public function setUnitPriceAttribute($unit_price_in_decimal)
	{
		$this->unit_price_in_pence = $unit_price_in_decimal * 100;
	}

	public function getUnitPriceAttribute()
	{
		return $this->unit_price_in_pence / 100;
	}

	public function setGrossPriceAttribute($gross_price_in_decimal)
	{
		$this->gross_price_in_pence = $gross_price_in_decimal * 100;
	}

	public function getGrossPriceAttribute()
	{
		return $this->gross_price_in_pence / 100;
	}

	/**
	 * Return a created presenter.
	 *
	 * @return Robbo\Presenter\Presenter
	 */
	public function getPresenter()
	{
		return new SalePresenter($this);
	}

}