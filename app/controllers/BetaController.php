<?php

class BetaController extends BaseController {

    private function pullFacebookEvents(){
        $client = new \Guzzle\Http\Client('https://graph.facebook.com/v3.1', array(
            'request.options' => array(
                'query' => array(
                    //'client_id' => Config::get('instagram.client_id'),//no longer required.
                    'access_token' => Config::get('facebook.access_token'),
                ),
            ),
        ));


        ## temp fix -- api problem
        $futureEvents = [];
        try {
            $request = $client->get('203933066322000/events');
            $response = $request->send();
        } catch (Exception $e) {
            return $futureEvents; // just return empty array
        }
        



        // $client2 = new \Guzzle\Http\Client('https://facebook.com/events');
        // $request2 = $client2->get('151230655829890');
        // $response2 = $request2->send();
        // if ($response2->isSuccessful()) {
        //     $output = (string) $response2->getBody();
        //     $output_array = [];
        //     preg_match('/<p>([^<]+)<\/p>/', $output, $output_array);
        //     $this->debug_to_console($output); 
        // }


        if ($response->isSuccessful()) {

                //echo "Successful response from Instagram.<br>";
                //echo "See console for objects recieved.<br>";
                //$this->debug_to_console($response->json());

                $result = $response->json();
                $events = $result['data'];
                //($result['data'] is an array of all photo objects.
                

                foreach ($events as $event) {
                    if (strtotime($event['start_time']) > time()) {
                        // echo "already found.";
                        //$this->debug_to_console("New future event(s) found!");
                        // just directly send the event as fb event
                        $futureEvents []= $event; 
                        continue;
                    }
                }


                //$next_url = array_get($result, 'pagination.next_url');
            } else {
                $this->debug_to_console("Something bad happened, facebook doesn't like us!");
                $this->debug_to_console("Events API not working!");
            }


        //echo "Database updated with new content (if any).<br>";
        return $futureEvents;
    }

    public function putVisibility($id, $action)
    {
        $photo = InstagramPhoto::findOrFail($id);

        if ($action === 'hide') {
            $photo->hidden = true;
        } else {
            $photo->hidden = false;
        }

        $photo->save();

        return Redirect::action('Admin\InstagramPhotosController@getIndex')
            ->with('success', 'Instagram Photo has been successfully updated');
    }



    public function getBuyMembership()
    {
        // just using lockers 'redirect-to' view cause I'm lazy :P
        return View::make('lockers.redirect_to_shop')
            ->with('redirect_to', 'https://www.imperialcollegeunion.org/shop/club-society-project-products/electrical-engineering-products/21966/electrical-engineering-membership-18-19');
    }

    public function getIndex()
    {
        //get Sponsors from DB
        $sponsors = Sponsor::sorted()
            ->get();
        $committee = CommitteeMember::sorted()
            ->get();

        $events = $this->pullFacebookEvents();

        date_default_timezone_set("Europe/London");
        
        return View::make('beta.index')
            ->with('sponsors', $sponsors)
            ->with('committee', $committee)
            ->with('events', $events);
    }


    private function debug_to_console($data, $context = 'Debug in Console')
    {

        // Buffering to solve problems frameworks, like header() in this and not a solid return.
        ob_start();

        $output  = 'console.info( \'' . $context . ':\' );';
        $output .= 'console.log(' . json_encode( $data ) . ');';
        $output  = sprintf( '<script>%s</script>', $output );
        
        echo $output;
    }
}
