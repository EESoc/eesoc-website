<?php
use Zend\Http\Client;
use Zend\Http\Client\Cookies;
use Zend\Http\Response;
use Zend\Http\Request;

class EActivitiesClient {

	const URL_BASE = 'https://eactivities.union.imperial.ac.uk/';
	const URL_ADMIN_CSP_DETAILS = 'https://eactivities.union.imperial.ac.uk/admin/csp/details';
	const URL_AJAX_HANDLER = 'https://eactivities.union.imperial.ac.uk/common/ajax_handler.php';
	const URL_MEMBERS_REPORT = 'https://eactivities.union.imperial.ac.uk/common/data_handler.php?id=%d&type=csv&name=Members_Report';

	protected $client;

	public function __construct(Client $client)
	{
		$this->client = $client;
		$this->client->setOptions(array(
			'sslverifypeer' => false,
			'keepalive' => true
		));
	}

	public function getCookies()
	{
		return $this->client->getCookies();
	}

	public function setCookies($cookies)
	{
		$this->client->setCookies($cookies);
	}

	public function signIn($username, $password)
	{
		$response = $this->getAjaxHandlerResponse(array(
			'ajax' => 'login',
			'name' => $username,
			'pass' => $password,
			'objid' => '1'
		));

		return $this->isSignedIn();
	}

	public function isSignedIn(Response $response = null)
	{
		if ( ! isset($response)) {
			$response = $this->openPage(static::URL_BASE);
		}

		return ($response->isSuccess() && str_contains($response->getBody(), 'Log out'));
	}

	public function getCurrentAndOtherRoles()
	{
		$response = $this->getAjaxHandlerResponse(array(
			'ajax' => 'setupinlineinfo',
			'navigate' => '1'
		));
		$body = $response->getBody();

		$result = array(
			'current' => null,
			'others' => array()
		);

		preg_match('/<p class="currentrole">([^<]+)<\/p>/', $body, $output_array);
		if (isset($output_array[1])) {
			$result['current'] = $output_array[1];
		}

		preg_match_all('/<span class="changerole" onclick="changeRole\(this, \'(\d+)\'\)">([^<]+)<\/span>/', $body, $output_array);
		if (isset($output_array[1])) {
			foreach ($output_array[1] as $key => $role_key) {
				$result['others'][$role_key] = $output_array[2][$key];
			}
		}

		return $result;
	}

	public function getMembersList()
	{
		$response = $this->openPage(static::URL_ADMIN_CSP_DETAILS);
		if ( ! $this->isSignedIn($response)) {
			return null; // @todo raise exception?
		}

		$response = $this->activateTabs('395');
		$body = $response->getBody();

		preg_match('/event="createDataFile\(eObj, \'(\d+)\', \'csv\', \'Members_Report\'\);"/', $body, $output_array);
		if (isset($output_array[1])) {
			$file_id = $output_array[1];

			$this->client->resetParameters();
			$this->client->setUri(sprintf(static::URL_MEMBERS_REPORT, $file_id));
			$this->client->setMethod(Request::METHOD_POST);

			$response = $this->client->send();
			$body = $response->getBody();

			$result = explode("\n", trim($body));

			// Get header
			$headers = str_getcsv(array_shift($result));
			$headers = array_map(function($original) {
				return Str::snake(Str::camel($original));
			}, $headers);

			// Format rows
			$result = array_map(function($original) use ($headers) {
				$row = str_getcsv($original);
				$new_row = array();
				foreach ($headers as $key => $header) {
					$new_row[$header] = $row[$key];
				}
				return $new_row;
			}, $result);


			return $result;
		} else {
			return null;
		}
	}

	public function changeRole($role_id)
	{
		return $this->getAjaxHandlerResponse(array(
			'ajax' => 'changerole',
			'navigate' => '1',
			'id' => $role_id,
		));
	}

	protected function activateTabs($navigate)
	{
		return $this->getAjaxHandlerResponse(array(
			'ajax' => 'activatetabs',
			'navigate' => $navigate,
		));
	}

	protected function getClubSocietyPageResponse()
	{
		return $this->openPage(static::URL_ADMIN_CSP_DETAILS);
	}

	protected function getBasePageResponse()
	{
		return $this->openPage(static::URL_BASE);
	}

	protected function openPage($url)
	{
		$this->client->resetParameters();
		$this->client->setUri($url);
		$this->client->setMethod(Request::METHOD_GET);

		return $this->client->send();
	}

	protected function getAjaxHandlerResponse($params)
	{
		$this->client->resetParameters();
		$this->client->setUri(static::URL_AJAX_HANDLER);
		$this->client->setMethod(Request::METHOD_POST);
		$this->client->setParameterPost($params);

		return $this->client->send();
	}

}