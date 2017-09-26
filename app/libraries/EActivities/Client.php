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
    const YEAR_CODE = '17-18';

    const PATH_CSP_DETAILS          = '/API/CSP/603';
    const PATH_COMMITTEE_REPORT     = '/API/CSP/603/reports/committee?year={constant(self::YEAR_CODE)}';
    const PATH_PRODUCTS_REPORT      = '/API/CSP/603/reports/products?year={constant(self::YEAR_CODE)}';
    const PATH_PRODUCT_INFO         = '/API/CSP/603/products/%d';
    const PATH_PRODUCT_SALES        = '/API/CSP/603/products/%d/sales';
    const PATH_MEMBERS_REPORT       = '/API/CSP/603/reports/members?year={constant(self::YEAR_CODE)}';
    const PATH_PURCHASE_REPORT      = '/finance/income/shop/group/csv/%d';

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
        return $this->getJSONResponse(self::PATH_CSP_DETAILS);
    }

    public function getCommitteeList()
    {
        return $this->getJSONResponse(self::PATH_COMMITTEE_REPORT);
    }


    /**
     * Download and parse members report file
     *
     * @return array
     */
    public function getMembersList()
    {
        return $this->getJSONResponse(self::PATH_MEMBERS_REPORT);
    }

    public function getProductList()
    {
        return $this->getJSONResponse(self::PATH_PRODUCTS_REPORT);
    }

    public function getProductInfo($product_id)
    {
        return $this->getJSONResponse(sprintf(self::PATH_PRODUCT_INFO, $product_id));
    }

    public function getPurchasesList($product_id)
    {
        //TODO: UPDATE THIS for new api!!!
        


        $response = $this->getJSONResponse(sprintf(self::PATH_PRODUCT_SALES, $product_id));
        // For each product, rewrite the date&time as d/m/Y format (still a string), for consistency with old data (as in sales table)
        // Using new method cause old method was now returning NULL for some reason
        // Returns an array which is then returned by function
        // Format from API: 2017-02-26 18:03:00 need to convert this to 2017-02-26 which is the only format db accepts.
        return array_map(function($product) {
            $product['SaleDateTime'] = DateTime::createFromFormat('Y-m-d H:i:s', $product['SaleDateTime'])->format('Y-m-d');
            return $product;
        }, $response);

    }


    /**
     * Send a GET request to API
     *
     * @param  string $request_url
     * @return JSON Response
     */
    protected function getJSONResponse($request_url)
    {
        
        $request =  $this->client->get($request_url, [
            'X-API-Key' => \Config::get('eactivities.api_key')
        ]);
        $response = $request->send();

        if ($response->isSuccessful()){
            return  $response->json();
        }
        else {
             //due to some formatting issues last %s must not have quotes!!, getBody(true) returns string but we must encode to remove formatting issues.
             return json_decode(sprintf('{"error": "unsuccessful response", "status_code":"%s", "response_body": %s }', $response->getStatusCode(), json_encode($response->getBody(true))));
        }
    }


}

class EActivitiesClientException extends \Exception
{
}
