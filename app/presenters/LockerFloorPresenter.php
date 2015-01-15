<?php

use Robbo\Presenter\Presenter;
use Illuminate\Support\Str;

class LockerFloorPresenter extends Presenter {

    public function presentAnchorName()
    {
        $slug = Str::slug($this->name);
        return "floor-{$slug}";
    }

}
