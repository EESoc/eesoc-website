<?php

use Guzzle\Http\Client;
use Guzzle\Http\Message\Response;
use Guzzle\Plugin\Cookie\Cookie;
use Guzzle\Plugin\Cookie\CookiePlugin;
use Guzzle\Plugin\Cookie\CookieJar\CookieJarInterface;
use Guzzle\Plugin\Cookie\CookieJar\ArrayCookieJar;

class EActivitiesClient {

	public static $URL_BASE = 'https://eactivities.union.imperial.ac.uk/';

	public static $PATH_COMMON_AJAX_HANDLER = '/common/ajax_handler.php';
	public static $PATH_ADMIN_CSP_DETAILS   = '/admin/csp/details';
	public static $PATH_MEMBERS_REPORT      = '/common/data_handler.php?id=%d&type=csv&name=Members_Report';

	public static $NAME_SESSION_COOKIE = 'ICU_eActivities';

	protected $client;

	protected $cookie_jar;

	public function __construct(Client $client)
	{
		$client->setBaseUrl(self::$URL_BASE);

		$client->setDefaultOption('exceptions', false);

		$this->cookie_jar = new ArrayCookieJar();
		$cookiePlugin = new CookiePlugin($this->cookie_jar);
		$client->addSubscriber($cookiePlugin);

		$this->client = $client;
	}

	public function getSessionId()
	{
		foreach ($this->cookie_jar->all() as $cookie) {
			if ($cookie->getName() == self::$NAME_SESSION_COOKIE) {
				return $cookie->getValue();
			}
		}

		return null;
	}

	public function setSessionId($session_id)
	{
		$cookie = new Cookie(array(
			'name' => self::$NAME_SESSION_COOKIE,
			'value' => $session_id,
			'domain' => 'eactivities.union.imperial.ac.uk'));
		$this->cookie_jar->add($cookie);
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
			$response = $this->getPageResponse('/');
		}

		return ($response->isSuccessful() && strpos($response->getBody(), 'Log out') !== false);
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
		foreach ($output_array[1] as $key => $role_key) {
			$result['others'][$role_key] = $output_array[2][$key];
		}

		return $result;
	}

	public function getMembersList()
	{
		$response = $this->getPageResponse(self::$PATH_ADMIN_CSP_DETAILS);
		if ( ! $this->isSignedIn($response)) {
			return null; // @todo raise exception?
		}

		$response = $this->activateTabs('395');
		$body = $response->getBody();

		preg_match('/event="createDataFile\(eObj, \'(\d+)\', \'csv\', \'Members_Report\'\);"/', $body, $output_array);
		if (isset($output_array[1])) {
			$file_id = $output_array[1];

			$request = $this->client->post(sprintf(self::$PATH_MEMBERS_REPORT, $file_id));
			$response = $request->send();
			$body = $response->getBody();

			$result = explode("\n", trim($body));

			// Get header
			$headers = str_getcsv(array_shift($result));
			$headers = array_map(function($original) {
				if ($original === 'CID') {
					return 'cid';
				} else {
					return Str::snake(Str::camel($original));
				}
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
			return array();
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

	protected function getPageResponse($path = null)
	{
		$request = $this->client->get($path);
		return $request->send();
	}

	protected function getAjaxHandlerResponse($params)
	{
		$request = $this->client->post(self::$PATH_COMMON_AJAX_HANDLER, array(), $params);
		return $request->send();
	}

}