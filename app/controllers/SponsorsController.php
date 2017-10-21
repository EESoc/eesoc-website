<?php

class SponsorsController extends BaseController {

    public function getIndex()
    {
        $sponsors = Sponsor::alphabetically()
            ->get();

        return View::make('sponsors.index')
            ->with('sponsors', $sponsors);
    }

}
