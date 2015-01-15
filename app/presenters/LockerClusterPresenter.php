<?php

use Robbo\Presenter\Presenter;

class LockerClusterPresenter extends Presenter {

    public function presentAnchorName()
    {
        $floor_slug = Str::slug($this->lockerFloor->name);
        $slug = Str::slug($this->name);
        return "floor-{$floor_slug}-cluster-{$slug}";
    }

}
