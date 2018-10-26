<?php

//use \Artisan; //can work without this, but it messes up php artisan console command if uncommented.
use Symfony\Component\Console\Output\StreamOutput;
use \Guzzle\Http\Client as Http_Client;

class ApiV2Controller extends BaseController {

    public function __construct()
    {
        // Skip CSRF filter
    }

    /*
     * POST FUNCTION CAUSE API.AI FOLLOWS THIS
    */

    public function postIndex()
    {
        if ( !Request::header('X-API-Key')) {
			return json_encode(json_decode('{"error": "malformed request"}'));
        }
        
        if ( Request::header('X-API-Key') != Config::get('eesocapi.eesoc_api_key')) {
			return json_encode(json_decode('{"error": "unauthorized request"}'));
        }

        $results = Input::get('result');
        $action = "no_action";

        if (is_array($results) && array_key_exists('action', $results)){
            $action = $results['action'];
        }

        switch ($action){
            // returning so no breaks required.
            case "no_action": {
                return json_encode(json_decode('{"error": "no action was specified."}'));
            }
            case "create_event": {
                return $this->postEvent($results);
            }

            case "sync_sales": {
                return $this->postSyncSales($results);
            }

            default: {
                return json_encode(json_decode('{"error": "malformed or invalid action type."}'));
            }
        }

        return;
    }

    public function postEvent($results)
    {
        /*if ( !Input::has('apikey')) {
			return json_encode(json_decode('{"error": "malformed request"}'));
        }
        
        if ( Input::get('apikey') != Config::get('eesocapi.eesoc_api_key')) {
			return json_encode(json_decode('{"error": "unauthorized request"}'));
        }*/

        

        //print_r(Request::header('X-API-Key'));
        //$response =  $this->client->post(Request::url(), [
        //    'Content-type' => 'application/json'
        //]);
        $params; //= (object)[]; //emty object, not neccessary gets overwritten later
        //$results = Input::get('result');

        if (is_array($results) && array_key_exists('parameters', $results)){
            $params = $results['parameters'];
        }
        else {
            $params = [];
        }

        //$speech = '';
        $fulfillment = (object)[];

        if (is_array($results) && array_key_exists('fulfillment', $results)){
            $fulfillment = $results['fulfillment'];
        }
        else {

        }
        //fulfillment['speech']
        $event_data = [
            'name' => '',
            'description' => '', //required!!
            'date' => '',
            'time' => ''
        ];

        //get name, date, time
        if (is_array($params) && array_key_exists('name', $params) && array_key_exists('date', $params) && array_key_exists('time-period', $params)){
            //$time_unit = str_replace("d","days",str_replace("h","hours",str_replace("m","minutes",$params['duration']['unit'])));
            //date("H:i:s", strtotime( $params['time'] . sprintf(" + %d %s", $params['duration']['amount'], $time_unit)));

            $event_data['name'] = $params['name'];
            $event_data['description'] = $params['description'];
            $event_data['date'] = $params['date'];
            $event_data['starts_at'] = explode('/', $params['time-period'])[0];
            $event_data['ends_at'] = explode('/', $params['time-period'])[1];
        }

        $speech_text = '[WEBHOOK] Insufficient data'; //default response

        //since all three are required, if one exits then all must exist.
        if ($event_data['name'] != ''){
            $speech_text = '[WEBHOOK] Got some data: ' . $event_data['name'] . '; ' . $event_data['date'] . '; ' . $event_data['starts_at'];
            

            /* Actual event creation */
            $event = new EventDay;
            $event->fill($event_data);
            $event->save();
            $speech_text = $speech_text . ' EVENT SAVED!';
            /* End */

            //Slack needs double quotes for newlines!!
            $slack_text = "[WEBHOOK-SLACK] Received create event command.\nName: " . $event_data['name'] . "\nDate: " . $event_data['date'] . "\nStart_Time: " . $event_data['starts_at'] . "\nEnd_Time: " . $event_data['ends_at'] . "\nEVENT SAVED!";//str_replace('; ','"\n"', str_replace(': ','"\n"',$speech_text)); 

            $params['slack'] = [
                "text" => $slack_text //str_replace('; ','\n',$speech_text) //user-friendly //This is a line of text.\nAnd this is another one.
            ];


            /*$params['slack'] = [
                "attachments" => [
                    "fallback" => $speech_text,
                    "color" => "#36a64f",
                    "pretext" => "",
                    "author_name" => "EESoc Events API",
                    "author_link" => "http://flickr.com/bobby/",
                    "author_icon" => "http://flickr.com/icons/bobby.jpg",
                    "title" => $event_data['name'],
                    "title_link" => "https://eesoc.com/events/",
                    "text" => $event_data['description'],
                    "fields" => [
                        "date" => $event_data['date'],
                        "time" => $event_data['starts_at'],
                        "short" => false
                    ],
                    "image_url" => "http://my-website.com/path/to/image.jpg",
                    "thumb_url" => "http://example.com/path/to/thumb.png",
                    "footer" => "Slack API",
                    "footer_icon" => "https://platform.slack-edge.com/img/default_application_icon.png",
                    "ts" => 123456789
                ]
                ];*/
        }

        //$event_data

        /*
        $event_data['starts_at'] */
        //needed for slack, can have newline
        
        
        
        $data = [
            'speech' => $speech_text,
            'displayText' => $speech_text,
            'data' => $params,
            'recieved' => Input::all()
        ];

        //json_decode('{"speech": "Test", "displayText": "More Test"}')



        return $this->createJSONResponse($data);
    }

    public function postSyncSales(){
        $params = []; //Don't need to parse/read any post data, just need to know that sync is requested

        $stream = fopen('php://temp/sync', 'r+');   //read+write mode
        Artisan::call('eactivities:sales:sync', [], new StreamOutput($stream)); //send ref to stream handle

        rewind($stream);                            //point handle back to beginning
        $output = stream_get_contents($stream);
        fclose($stream);                            //close after use

        
        
        

        //Slack needs double quotes for newlines!!
        $slack_text = "[WEBHOOK-SLACK] Received sales sync command.\nOutput: " .  $output;
        $speech_text = preg_replace('[WEBHOOK-SLACK]', '[WEBHOOK]', $slack_text);

        $params['slack'] = [
            "text" => $slack_text
        ];


        $data = [
            'speech' => $speech_text,
            'displayText' => $speech_text,
            'data' => $params,
            'recieved' => Input::all()
        ];

        //special for dinner only
        //Now disabled
        /*$chunks = explode("\n", $slack_text); //SPLIT BY NEWLINE
        $dinner_text = "";
        foreach($chunks as $chunk){
            //Add 'SalesStat' and 'Dinner' keyword sentences
            if (strpos($chunk,"Dinner") !== false || strpos($chunk,"SalesStat") !== false){
                $dinner_text .= ($chunk . "\n");
            }
        }
        if ($dinner_text != "") {
            //Only send wehn not empty as empty string may cause errors
            $this->sendJSONToWebhook(json_encode([ "text" => $dinner_text ]), Config::get('slack.webhook.dinner_channel'));
        }*/

        //Send full log to logs channel
        $this->sendJSONToWebhook(json_encode($data['data']['slack']), Config::get('slack.webhook.logs_channel'));

        return $this->createJSONResponse($data);
    }

    protected function createJSONResponse($data){
        $response = Response::make(json_encode($data), 200);
        $response->header('Content-Type', 'application/json');
        return $response;
    }

    /* Sends a copy of the output to  */
    protected function sendJSONToWebhook($json, $webhook){
        $client = new Http_Client('https://hooks.slack.com');
        return $client->post($webhook, ['Content-type' => 'application/json'], $json)->send();
    }
}
