<?php
namespace Admin;

use \Input;
use \InstagramPhoto;
use \Redirect;
use \View;

class InstagramPhotosController extends BaseController {

	public function getIndex()
	{
		return View::make('admin.instagram_photos.index')
			->with('photos', InstagramPhoto::latest()->get());
	}

	public function putVisibility($id, $action)
	{
		$photo = InstagramPhoto::findOrFail($id);

		if ($action === 'hide') {
			$photo->hidden = true;
		} else {
			$photo->hidden = false;
		}

		$photo->save();

		return Redirect::action('Admin\InstagramPhotosController@getIndex')
			->with('success', 'Instagram Photo has been successfully updated');
	}

}