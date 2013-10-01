<?php

class CronController extends BaseController {

	public function getInstagram()
	{
		$ids = array();
		$next_url = null;

		$client = new Guzzle\Http\Client('https://api.instagram.com/{version}', array(
			'version' => 'v1',
			'tag_name' => 'eesoc',
			'request.options' => array(
				'query' => array(
					'client_id' => Config::get('instagram.client_id'),
				),
			),
		));

		do {
			if (isset($next_url)) {
				$next_url_params = parse_url($next_url);
				$request = $client->get($next_url_params['path'] . '?' . $next_url_params['query']);
			} else {
				$request = $client->get('tags/{tag_name}/media/recent');
			}

			$response = $request->send();

			if ($response->isSuccessful()) {
				$result = $response->json();
				foreach ($result['data'] as $photo) {
					if (in_array($photo['id'], $ids)) {
						continue;
					}
					
					$ids[] = $photo['id'];
					InstagramPhoto::refresh($photo);
				}


				$next_url = array_get($result, 'pagination.next_url');
			} else {
				$next_url = null;
			}

		} while (isset($next_url));
	}

}