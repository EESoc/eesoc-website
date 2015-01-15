<?php

use Robbo\Presenter\PresentableInterface;

class LockerFloor extends Eloquent implements PresentableInterface {

    public $timestamps = false;

    public function lockerClusters()
    {
        return $this->hasMany('LockerCluster');
    }

    public function scopeSorted($query)
    {
        return $query->orderBy('floor');
    }

    /**
     * Return a created presenter.
     *
     * @return Robbo\Presenter\Presenter
     */
    public function getPresenter()
    {
        return new LockerFloorPresenter($this);
    }

}
