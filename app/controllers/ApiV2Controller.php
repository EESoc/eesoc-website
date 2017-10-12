<?php

class ApiV2Controller extends BaseController {

    public function __construct()
    {
        // Skip CSRF filter
    }

    public function getIndex()
    {
        return View::make('beta.index');
    }

    public function postEvent()
    {
        /*if ( !Input::has('apikey')) {
			return json_encode(json_decode('{"error": "malformed request"}'));
        }
        
        if ( Input::get('apikey') != Config::get('eesocapi.eesoc_api_key')) {
			return json_encode(json_decode('{"error": "unauthorized request"}'));
        }*/

        if ( !Request::header('X-API-Key')) {
			return json_encode(json_decode('{"error": "malformed request"}'));
        }
        
        if ( Request::header('X-API-Key') != Config::get('eesocapi.eesoc_api_key')) {
			return json_encode(json_decode('{"error": "unauthorized request"}'));
        }

        //print_r(Request::header('X-API-Key'));
        //$response =  $this->client->post(Request::url(), [
        //    'Content-type' => 'application/json'
        //]);
        $params; //= (object)[]; //emty object, not neccessary gets overwritten later
        $results = Input::get('result');
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
        if (is_array($params) && array_key_exists('name', $params) && array_key_exists('date', $params) && array_key_exists('time', $params)){
            $event_data['name'] = $params['name'];
            $event_data['description'] = $params['description'];
            $event_data['date'] = $params['date'];
            $event_data['starts_at'] = $params['time'];
            $event_data['ends_at'] = date("H:i", strtotime( $params['time'] . " + " . $params['duration']['amount'] . " " . $params['duration']['unit']));
        }

        $speech_text = '[WEBHOOK] Insufficient data'; //default response

        //since all three are required, if one exits then all must exist.
        if ($event_data['name'] != ''){
            $speech_text = '[WEBHOOK] Got some data: ' . $event_data['name'] . ' ' . $event_data['date'] . ' ' . $event_data['time'];
            
            $event = new EventDay;
            $event->fill($event_data);
            $event->save();
            $speech_text = $speech_text . ' EVENT SAVED!';

            $params['slack'] = [
                "text" => $speech_text
            ];
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



        $response = Response::make(json_encode($data), 200);
        
        $response->header('Content-Type', 'application/json');

        //return json_encode(Input::all());
        return $response;
        
        //print_r(Input::all());
    }
}
