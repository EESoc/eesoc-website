<?php
namespace EEPeople;

use \Guzzle\Http\Client as Http_Client;
use \Guzzle\Http\Exception\ClientErrorResponseException;
use \Guzzle\Http\Message\Response;
use \Guzzle\Plugin\Cookie\Cookie;
use \Guzzle\Plugin\Cookie\CookiePlugin;
use \Guzzle\Plugin\Cookie\CookieJar\CookieJarInterface;
use \Guzzle\Plugin\Cookie\CookieJar\ArrayCookieJar;
use \ImperialCollegeCredential;

class Client {

	public static $URL_BASE = 'https://intranet.ee.ic.ac.uk/';

	public static $PATH_STUDENT_GROUP = '/electricalengineering/eepeople/pplsg.asp';
	public static $PATH_PERSON        = '/electricalengineering/eepeople/person.asp';
	public static $PATH_SIGN_IN       = '/electricalengineering/intrnl/index_net.asp';
	public static $PATH_SESSION_INIT  = '/electricalengineering/intrnl/index_net_ses.aspx';

	public static $PARAMS_STUDENT_GROUP_INDEX = array('g' => 'NIL');
	public static $PARAMS_PERSON = array('s' => 'NIL', 'id' => null);
	public static $REGEX_STUDENT_GROUPS = '/<li><a class="mylink" HREF=pplsg.asp\?g=(?P<group_id>[\w]{2})>\s*(?P<group_name>.+?)\s*<\/a><\/li>/';

	public static $REGEX_PERSON_IDS_IN_GROUP = '/person\.asp.+?id=(?P<person_id>\d+)/';

	public static $COOKIE_SESSION_NAMES = array('ASP.NET_SessionId', 'ASPSESSIONIDCABDSQSB');

	protected $client;

	protected $cookie_jar;

	/**
	 * @param Http_Client $client
	 */
	public function __construct(Http_Client $client)
	{
		$client->setBaseUrl(self::$URL_BASE);

		$client->setDefaultOption('exceptions', false);

		$this->cookie_jar = new ArrayCookieJar();
		$cookiePlugin = new CookiePlugin($this->cookie_jar);
		$client->addSubscriber($cookiePlugin);

		$this->client = $client;
	}

	/**
	 * Sign in a given credential
	 * 
	 * @param  ImperialCollegeCredential $credential
	 * @return boolean
	 */
	public function signIn(ImperialCollegeCredential $credential)
	{
		$auth_params = array($credential->getUsername(), $credential->getPassword());
		$request = $this->client->get(self::$PATH_SIGN_IN, null, array(
			'auth' => $auth_params,
		));

		try {
			$response = $request->send();

			// Persist auth
			$this->client->setDefaultOption('auth', $auth_params);

			// Set server side session data
			$params = array(
				'logon' => $credential->getUsername(),
				'np' => self::$PATH_STUDENT_GROUP.'?'.http_build_query(self::$PARAMS_STUDENT_GROUP_INDEX),
			);
			$response = $this->client->post(self::$PATH_SESSION_INIT, null, $params)->send();

			return true;
		} catch (ClientErrorResponseException $e) {
			return false;
		}
	}

	/**
	 * Get a list of student groups
	 * 
	 * @return array
	 */
	public function getStudentGroups()
	{
		$response = $this->getPageResponse(self::$PATH_STUDENT_GROUP, self::$PARAMS_STUDENT_GROUP_INDEX);
		$body = (string) $response->getBody();

		preg_match_all(self::$REGEX_STUDENT_GROUPS, $body, $output_array);

		$result = array();

		$matches_count = count($output_array[0]);
		for ($i = 0; $i < $matches_count; ++$i) {
			$result[] = array(
				'id' => $output_array['group_id'][$i],
				'name' => $output_array['group_name'][$i]
			);
		}

		return $result;
	}

	/**
	 * Get a list of student Ids in a group
	 * 
	 * @param  string $group_id
	 * @return array
	 */
	public function getStudentIdsInGroup($group_id)
	{
		$response = $this->getPageResponse(self::$PATH_STUDENT_GROUP, array('g' => $group_id));
		$body = (string) $response->getBody();

		preg_match_all(self::$REGEX_PERSON_IDS_IN_GROUP, $body, $output_array);

		$result = array();

		$matches_count = count($output_array[0]);
		for ($i = 0; $i < $matches_count; ++$i) {
			$result[] = (int) $output_array['person_id'][$i];
		}

		return $result;
	}

	/**
	 * Get a Person's details
	 * 
	 * @param  integer $student_id
	 * @return array
	 */
	public function getPerson($student_id)
	{
		$params = self::$PARAMS_PERSON;
		$params['id'] = $student_id;
		$response = $this->getPageResponse(self::$PATH_PERSON, $params);
		$body = (string) $response->getBody();

		if (strpos($body, 'EE ID does not exist or Person is Ex-Directory.') !== false) {
			return false;
		}

		$result = array();

		$result['id'] = (int) $student_id;

		preg_match('/<h2><font color="black"><b>\s*(.+?)\s*<\/font><\/b><\/h2>/', $body, $output_array);
		$result['name'] = @$output_array[1];
		foreach (array('Mr', 'Mrs', 'Miss', 'Ms', 'Dr') as $title) {
			if (strpos($result['name'], $title) === 0) {
				$result['name'] = substr(strstr($result['name'], ' '), 1);
			}
		}

		preg_match('/Student Group: <a class="mylink" HREF="pplsg.asp\?g=([\w]{2})">\s*(.+?)\s*<\/A>/', $body, $output_array);
		$result['group_id'] = @$output_array[1];
		$result['group_name'] = @$output_array[2];

		preg_match('/<br>Tutor: (.+?)<br>/', $body, $output_array);
		$result['tutor_name'] = @$output_array[1];
		if ($result['tutor_name'] === '<br>') {
			$result['tutor_name'] = null;
		}

		preg_match('/Category: <a class="mylink" HREF="pplsc.asp\?c=(\w+?)">\s*(.+?)\s*<\/A>/', $body, $output_array);
		$result['category_id'] = @$output_array[1];
		$result['category_name'] = @$output_array[2];

		preg_match('/Research Group: <a class="mylink" HREF="pplrg.asp\?g=(\w+?)">\s*(.+?)\s*<\/A>/', $body, $output_array);
		$result['research_group_id'] = @$output_array[1];
		$result['research_group_name'] = @$output_array[2];

		preg_match('/<br>Username: (.+?)<br>/', $body, $output_array);
		$result['username'] = @$output_array[1];

		preg_match('/<a class="mylink" href="mailto:(.+?)">.+?<\/a>/', $body, $output_array);
		$result['email'] = @$output_array[1];

		preg_match('/<img src="(.+?)" width=135 height=165 alt="Photo" border=0 align="RIGHT">/', $body, $output_array);
		$result['image_path'] = @$output_array[1];

		return $result;
	}

	/**
	 * Get the Image of a Person
	 * 
	 * @param  array $person
	 * @return array
	 */
	public function getImageOfPerson($person)
	{
		$result = array();

		$image_response = $this->client->get($person['image_path'])->send();
		if ($image_response->isSuccessful()) {
			$result['content_type'] = $image_response->getContentType();
			$result['blob']         = $image_response->getBody(); // Original Size: 324x432
		} else {
			$result['content_type'] = null;
			$result['blob']         = null;
		}

		return $result;
	}

	/**
	 * Get the student
	 * 
	 * @return integer
	 */
	public function getStudentsCount()
	{
		$sum = 0;
		foreach ($this->getStudentGroups() as $group) {
			$sum += count($this->getStudentIdsInGroup($group['id']));
		}
		return $sum;
	}

	/**
	 * Send a GET request with params
	 * 
	 * @param  string $path
	 * @param  array $query
	 * @return Response
	 */
	protected function getPageResponse($path = null, $query = null)
	{
		$request = $this->client->get($path, null, array('query' => $query));
		return $request->send();
	}

}