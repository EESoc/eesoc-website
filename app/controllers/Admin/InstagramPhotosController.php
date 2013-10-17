<?php
namespace Admin;

use \Input;
use \InstagramPhoto;
use \Redirect;
use \View;

class InstagramPhotosController extends BaseController {

	public function index()
	{
		return View::make('admin.instagram_photos.index')
			->with('photos', InstagramPhoto::latest()->get());
	}

	public function update($id)
	{
		$photo = InstagramPhoto::findOrFail($id);

		if (Input::get('action') === 'hide') {
			$photo->hidden = true;
		} else {
			$photo->hidden = false;
		}

		$photo->save();

		return Redirect::route('admin.instagram-photos.index')
			->with('success', 'Instagram Photo has been successfully updated');
	}

}