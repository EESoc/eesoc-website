<?php

class HomeController extends BaseController {

	public function getWelcome()
	{
		return View::make('pages.welcome');
	}

	public function getPhotos()
	{
		$ids = array();
		$photos = array();
		$pages = 0;
		$min_tag_id = null;

		$client = new Guzzle\Http\Client('https://api.instagram.com/{version}', array(
			'version' => 'v1',
			'request.options' => array(
				'query' => array(
					'client_id' => 'db0e416dc8ae4eba8a84d4c248b4be11',
				),
			),
		));

		do {
			$request = $client->get('tags/eesoc/media/recent');
			$request->getQuery()->set('max_tag_id', $min_tag_id);
			$response = $request->send();

			if ($response->isSuccessful()) {
				$result = $response->json();
				foreach ($result['data'] as $photo) {
					if (in_array($photo['id'], $ids)) {
						continue;
					}
					
					$ids[] = $photo['id'];
					$photos[] = array(
						'link' => $photo['link'],
						'low_resolution' => $photo['images']['low_resolution'],
						'standard_resolution' => $photo['images']['standard_resolution'],
					);
				}
				
				$min_tag_id = $result['pagination']['min_tag_id'];
				$pages++;
			} else {
				$min_tag_id = null;
			}

		} while ($min_tag_id && $pages < 5);
		return Response::json($photos);
	}

}