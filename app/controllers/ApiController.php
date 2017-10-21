<?php

use \Guzzle\Http\Client;
use \Guzzle\Http\Message\Response;
use Carbon\Carbon;

class ApiController extends BaseController {

    public function __construct()
    {
        // Skip CSRF filter
    }

    public function getMe()
    {
        $user = User::find(ResourceServer::getOwnerId());

        return [
            'username' => $user->username,
            'name' => $user->name,
            'email' => $user->email,
            'admin' => (bool) $user->is_admin,
            'description' => $user->extras,
        ];
    }	
	
    public function postEventCID()
    {
		if ( !Input::has('apikey')) {
			App::abort(401, 'Malformed request.');
		}
		
		if ( Input::get('apikey') != Config::get('eesocapi.eesoc_api_key')) {
			App::abort(403, 'Unauthorized.');
		}	
		
		//If event id is noe defined, use the current event
		if (!Input::has('event')){
			$eventsQuery = EventDay::where('date', '>=', DB::raw('NOW() - INTERVAL 1 DAY'))->orderBy('date');
		}else{
			$eventsQuery = EventDay::where("id","<>",Input::get('event'));
		}
		
		$event = $eventsQuery->firstOrFail();
		
		if (Input::has('cid')){
		
			$cid = strtoupper(Input::get('cid'));
			$url = 'https://icsql1.cc.ic.ac.uk/securitycard/securitycard.asmx';
		
			$req = <<<PHPXMLEOF
<?xml version="1.0" encoding="utf-8"?>
<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
  <soap:Body>
	<getCID xmlns="http://tempuri.org/">
	  <searchterm>$cid</searchterm>
	</getCID>
  </soap:Body>
</soap:Envelope>
PHPXMLEOF;
     		
			$icsqlclient = new Guzzle\Http\Client($url, array(
				'request.options' => array(
					'headers' => array(
						'Host' => "icsql1.cc.ic.ac.uk",
						'Content-Type' => "text/xml; charset=utf-8",
						'Content-Length' => strlen($req),
						'SOAPAction' => "http://tempuri.org/getCID",
					),
				),
			));
			
			// Create a POST request and fire
			$request = $icsqlclient->post($url, array(
				'timeout' => 2			
			), $req);
			
			$xmlstr = "";
			
			try {
				$response = $request->send();
				$xmlstr=$response->getBody(true);
			} catch (Guzzle\Http\Exception\BadResponseException $e) {

				App::abort(400, 'Bad request.');
				/**
				echo 'Uh oh! ' . $e->getMessage();
				echo 'HTTP request URL: ' . $e->getRequest()->getUrl() . "\n";
				echo 'HTTP request: ' . $e->getRequest() . "\n";
				echo 'HTTP response status: ' . $e->getResponse()->getStatusCode() . "\n";
				echo 'HTTP response: ' . $e->getResponse() . "\n";
				**/
			}
			
			$xml = new SimpleXMLElement($xmlstr);
			
			foreach($xml->getDocNamespaces() as $strPrefix => $strNamespace) {
				$xml->registerXPathNamespace($strPrefix,$strNamespace);
			}
			
			$cidinfo = $xml->children('soap', TRUE)->Body->children('', TRUE)->children('', TRUE)->children();
			$info_array = json_decode(json_encode($cidinfo),TRUE);
			
			$user = User::where('username', '=', $cidinfo->login)->first();
			
			// Find or create
            if ( ! $user) {
                $user = new User;
				$user->username = $cidinfo->login;
				$user->name     = $cidinfo->firstname." ".$cidinfo->surname;
				$user->email    = $cidinfo->email;
				$user->cid      = $cidinfo->cid;
				$user->extra    = $cidinfo->department;
				$user->is_member = false;            
			}		
			
		}else if (Input::has('username')){
			$user = User::where('username', '=', Input::get('username'))->firstOrFail();
		}else{
			App::abort(401, 'Malformed request.');		
		}			

		
		$designworkshop = [
"zaw14",
"vks114",
"aja114",
"tcf14",
"nag113",
"cm2715",
"nar213",
"kjr115",
"ar5115",
"sgk13",
"te515",
"bg613",
"af814",
"lp1514",
"aa16514",
"ak7013",
"ajw114",
"sdd113",
"cmt114",
"chl214",
"np1915",
"lb3814",
"ncc214",
"wl4715",
"rb1716",
"am11015",
"dlv15",
"sg5913",
"aa18514",
"sa8715",
"zkl14",
"hi116",
"hd1316",
"jhc213",
"ar3814",
"pva13",
"daw15",
"gpr16",
"wjw13",
"oja13",
"fm3915",
"ps3416",
"ojf13",
"hom15",
"zct15",
"jma114",
"zmc14",
"ys5413",
"jl13415",
"mc7415",
"tw2813",
"xl5512",
"cmm15",
"pl2013",
"gyh13",
"vk1014",
"scl214",
"cmp14",
"sw6714",
"kbh15",
"awl14"
];
		//design workshop is 95
		$entry =  $event->id != 95 || in_array($user->username, $designworkshop);
		
		if ($entry){
			//Attach to event
			$eveattach = $user->events()->where("events.id","=",$event->id)->count();
			if ($eveattach == 0){
				$user->events()->attach($event->id);
			}
		}
		
		
		$user->save();
		
		return [
		"entry"=>$entry,
		"user_id"=>$user->id,
		"username"=>$user->username,
		"name"=>$user->name,
		"event_id"=>$event->id,
		"event_name"=>$event->name,		
		];
		
    }
	
    public function postEventName()
    {
		if ( !Input::has('apikey')) {
			App::abort(401, 'Malformed request.');
		}
		
		if ( Input::get('apikey') != Config::get('eesocapi.eesoc_api_key')) {
			App::abort(403, 'Unauthorized.');
		}	
		
		//If event id is noe defined, use the current event
		if (!Input::has('event')){
			$eventsQuery = EventDay::where('date', '>=', DB::raw('NOW() - INTERVAL 1 DAY'))->orderBy('date');
		}else{
			$eventsQuery = EventDay::where("id","<>",Input::get('event'));
		}
		
		$event = $eventsQuery->firstOrFail();
		
		return [
		"event_id"=>$event->id,
		"event_name"=>$event->name,
		
		];		
    }
	
	

}
