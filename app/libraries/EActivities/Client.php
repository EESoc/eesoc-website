<?php
namespace EActivities;

use \Guzzle\Http\Client as Http_Client;
use \Guzzle\Http\Message\Response;
use \Guzzle\Plugin\Cookie\Cookie;
use \Guzzle\Plugin\Cookie\CookieJar\ArrayCookieJar;
use \Guzzle\Plugin\Cookie\CookieJar\CookieJarInterface;
use \Guzzle\Plugin\Cookie\CookiePlugin;
use \ImperialCollegeCredential;
use \Str;

class Client {

	const URL_BASE = 'https://eactivities.union.imperial.ac.uk/';

	const PATH_COMMON_AJAX_HANDLER = '/common/ajax_handler.php';
	const PATH_ADMIN_CSP_DETAILS   = '/admin/csp/details';
	const PATH_MEMBERS_REPORT      = '/common/data_handler.php?id=%d&type=csv&name=Members_Report';

	const NAME_SESSION_COOKIE = 'ICU_eActivities';

	protected $client;

	protected $cookie_jar;

	/**
	 * @param Http_Client $client
	 */
	public function __construct(Http_Client $client)
	{
		$client->setBaseUrl(self::URL_BASE);

		$client->setDefaultOption('exceptions', false);

		$this->cookie_jar = new ArrayCookieJar();
		$cookiePlugin = new CookiePlugin($this->cookie_jar);
		$client->addSubscriber($cookiePlugin);

		$this->client = $client;
	}

	/**
	 * Get session Id
	 * 
	 * @return string
	 */
	public function getSessionId()
	{
		foreach ($this->cookie_jar->all() as $cookie) {
			if ($cookie->getName() == self::NAME_SESSION_COOKIE) {
				return $cookie->getValue();
			}
		}

		return null;
	}

	/**
	 * Set session Id
	 * 
	 * @param string $session_id
	 */
	public function setSessionId($session_id)
	{
		$cookie = new Cookie(array(
			'name' => self::NAME_SESSION_COOKIE,
			'value' => $session_id,
			'domain' => 'eactivities.union.imperial.ac.uk'));
		$this->cookie_jar->add($cookie);
	}

	/**
	 * Sign in a given credential
	 * 
	 * @param  ImperialCollegeCredential $credential
	 * @return boolean
	 */
	public function signIn(ImperialCollegeCredential $credential)
	{
		$response = $this->getAjaxHandlerResponse(array(
			'ajax' => 'login',
			'name' => $credential->getUsername(),
			'pass' => $credential->getPassword(),
			'objid' => '1'
		));

		return $this->isSignedIn();
	}

	/**
	 * Check if user is signed in.
	 * Will check the response of the root page if no response is given.
	 * 
	 * @param  Response  $response
	 * @return boolean
	 */
	public function isSignedIn(Response $response = null)
	{
		if ( ! isset($response)) {
			$response = $this->getPageResponse('/');
		}

		return ($response->isSuccessful() && strpos($response->getBody(), 'Log out') !== false);
	}

	/**
	 * Get user's currently selected and other roles
	 * 
	 * @return array
	 */
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

	/**
	 * Download and parse members report file
	 * 
	 * @return array
	 */
	public function getMembersList()
	{
		$response = $this->getPageResponse(self::PATH_ADMIN_CSP_DETAILS);
		if ( ! $this->isSignedIn($response)) {
			return null; // @todo raise exception?
		}

		$response = $this->activateTabs('395');
		$body = $response->getBody();

		preg_match('/event="createDataFile\(eObj, \'(\d+)\', \'csv\', \'Members_Report\'\);"/', $body, $output_array);
		if (isset($output_array[1])) {
			$file_id = $output_array[1];

			$request = $this->client->post(sprintf(self::PATH_MEMBERS_REPORT, $file_id));
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

	/**
	 * Change user's role
	 * 
	 * @param  integer|string $role_id
	 * @return Response
	 */
	public function changeRole($role_id)
	{
		return $this->getAjaxHandlerResponse(array(
			'ajax' => 'changerole',
			'navigate' => '1',
			'id' => $role_id,
		));
	}

	/**
	 * Activate tabs
	 * 
	 * @param  integer|string $navigate
	 * @return Response
	 */
	protected function activateTabs($navigate)
	{
		return $this->getAjaxHandlerResponse(array(
			'ajax' => 'activatetabs',
			'navigate' => $navigate,
		));
	}

	/**
	 * Send a GET request
	 * 
	 * @param  integer $path
	 * @return Response
	 */
	protected function getPageResponse($path = null)
	{
		$request = $this->client->get($path);
		return $request->send();
	}

	/**
	 * Send a POST request to the ajax handler
	 * 
	 * @param  array $params
	 * @return Response
	 */
	protected function getAjaxHandlerResponse($params)
	{
		$request = $this->client->post(self::PATH_COMMON_AJAX_HANDLER, array(), $params);
		return $request->send();
	}

}