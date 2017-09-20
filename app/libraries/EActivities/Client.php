<?php
namespace EActivities;

use \DateTime;
use \Guzzle\Http\Client as Http_Client;
use \Guzzle\Http\Message\Response;
use \Guzzle\Plugin\Cookie\Cookie;
use \Guzzle\Plugin\Cookie\CookieJar\ArrayCookieJar;
use \Guzzle\Plugin\Cookie\CookieJar\CookieJarInterface;
use \Guzzle\Plugin\Cookie\CookiePlugin;
use \ImperialCollegeCredential;
use \Str;

class Client {

    const URL_BASE = 'https://eactivities.union.ic.ac.uk';

    const PATH_COMMON_AJAX_HANDLER = '/';
    const PATH_CSP_DETAILS         = '/API/CSP/603';
    const PATH_COMMITEE_REPORT   = '/API/CSP/603/reports/committee?year=17-18';
    const PATH_FINANCE_INCOME_SHOP = '/finance/income/shop/603';
    const PATH_MEMBERS_REPORT      = '/API/CSP/603/reports/members?year=17-18';
    const PATH_PURCHASE_REPORT     = '/finance/income/shop/group/csv/%d';

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
     * @return ARRAY OF JSON
     */
    public function getBasicInfo()
    {
        $response = $this->getAjaxHandlerResponse(self::PATH_CSP_DETAILS);
        
        $html_content = $response->getBody(true);
        $json_array=array(
            
            'content'=>50,
            'html_content'=>$html_content
            );

        if ($response->isSuccessful()){
            return  $response->json();
        }
        else {
             //due to some formatting issues last %s must not have quotes!!, getBody(true) returns string but we must encode to remove formatting issues.
             return json_decode(sprintf('{"error": "unsuccessful response", "status_code":"%s", "response_body": %s }', $response->getStatusCode(), json_encode($response->getBody(true))));
        }

    }


    /**
     * Download and parse members report file
     *
     * @return array
     */
    public function getMembersList()
    {
        $response = $this->getAjaxHandlerResponse(self::PATH_MEMBERS_REPORT);
        
        if ($response->isSuccessful()){
            return  $response->json();
        }
        else {
             //due to some formatting issues last %s must not have quotes!!, getBody(true) returns string but we must encode to remove formatting issues.
             return json_decode(sprintf('{"error": "unsuccessful response", "status_code":"%s", "response_body": %s }', $response->getStatusCode(), json_encode($response->getBody(true))));
        }
    }

    public function getPurchasesList($product_id)
    {
        //TODO: UPDATE THIS for new api!!!
        $response = $this->getPageResponse(self::PATH_FINANCE_INCOME_SHOP);
        //if ( ! $this->isSignedIn($response)) {
            //return []; // @todo raise exception?
        //}

        // 1725: Purchases Summary
        $response = $this->activateTabs(['1725']);
        if ( ! $response->isSuccessful()) {
            return []; // @todo raise exception?
        }

        $request = $this->client->get(sprintf(self::PATH_PURCHASE_REPORT, $product_id));
        $response = $request->send();
        $body = $response->getBody();
        $result = $this->parseCsv($body);
        $result = array_map(function($product) use ($product_id) {
            $product['product_id'] = $product_id;
			$product['date'] = DateTime::createFromFormat('d/h/Y', $product['date']);
            return $product;
        }, $result);

        return $result;
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
     * Send a GET request to API
     *
     * @param  array $params
     * @return Response
     */
    protected function getAjaxHandlerResponse($request_url)
    {
        
        $request =  $this->client->get($request_url, [
            'X-API-Key' => \Config::get('eactivities.api_key')
        ]);
        return $request->send();
    }


}

class EActivitiesClientException extends \Exception
{
}
