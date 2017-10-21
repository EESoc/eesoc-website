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
        return $query->where('product_id', '=', Product::ID_EESOC_LOCKER);
    }

    public function getIsLockerAttribute()
    {
        return ($this->product_name === 'Single Locker');
    }

    public function setUnitPriceAttribute($unit_price_in_decimal)
    {
        $this->unit_price_in_pence = $unit_price_in_decimal * 100;
    }

    public function getUnitPriceAttribute()
    {
        return $this->unit_price_in_pence / 100;
    }

    public function getGrossPriceAttribute()
    {
        return ($this->unit_price_in_pence / 100) * ($this->quantity);
    }

    /*public function setDateAttribute($date)
    {
        $this->date = DateTime::createFromFormat('d/m/Y', $date)->format('Y-m-d');
    }

    public function getDateAttribute()
    {
        return DateTime::createFromFormat('Y-m-d', $this->date)->format('d/m/Y');
    }*/

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
