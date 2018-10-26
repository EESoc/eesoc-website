<?php

class HomeController extends BaseController {

    public function getWelcome()
    {
        return View::make('pages.welcome');
    }

    public function getPhotos()
    {
        $photos = InstagramPhoto::visible()
            ->latest()
            ->take(12)
            ->get();

        return Response::json($photos);
    }

}
