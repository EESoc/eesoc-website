<?php

class CareersFairController extends BaseController {

    public function getIndex()
    {
        $stands = CareersFairStand::alphabetically()
            ->get();

        return View::make('careersfair.index')
            ->with('stands', $stands);
    }

}
