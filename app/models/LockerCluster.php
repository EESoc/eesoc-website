<?php

use Robbo\Presenter\PresentableInterface;

class LockerCluster extends Eloquent implements PresentableInterface {

    public $timestamps = false;

    public function lockerFloor()
    {
        return $this->belongsTo('LockerFloor');
    }

    public function lockers()
    {
        return $this->hasMany('Locker');
    }

    public function scopeSorted($query)
    {
        return $query->orderBy('position');
    }

    /**
     * Return a created presenter.
     *
     * @return Robbo\Presenter\Presenter
     */
    public function getPresenter()
    {
        return new LockerClusterPresenter($this);
    }

}
