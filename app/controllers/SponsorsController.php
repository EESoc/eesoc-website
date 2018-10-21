<?php

class SponsorsController extends BaseController {

    public function getIndex()
    {
        $sponsors = Sponsor::sorted()
            ->get();

        return View::make('sponsors.index')
            ->with('sponsors', $sponsors);
    }

}
