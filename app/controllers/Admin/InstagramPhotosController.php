<?php
namespace Admin;

use \Input;
use \InstagramPhoto;
use \Redirect;
use \View;
use \Config as Config;

class InstagramPhotosController extends BaseController {

    public function getIndex()
    {
        return View::make('admin.instagram_photos.index')
            ->with('photos', InstagramPhoto::latest()->get());
    }

    public function getUpdate()
    {
        //echo "Sending request to Instagram.<br>";
        $ids = array();
        $next_url = null;

        $client = new \Guzzle\Http\Client('https://api.instagram.com/{version}', array(
            'version' => 'v1',
            'request.options' => array(
                'query' => array(
                    //'client_id' => Config::get('instagram.client_id'),//no longer required.
                    'access_token' => Config::get('instagram.access_token'),
                ),
            ),
        ));

        do {
            if (isset($next_url)) {
                
                $next_url_params = parse_url($next_url);
                $request = $client->get($next_url_params['path'] . '?' . $next_url_params['query']);
            } else {
                $request = $client->get('users/self/media/recent');
            }

            $response = $request->send();

            if ($response->isSuccessful()) {

                //echo "Successful response from Instagram.<br>";
                //echo "See console for objects recieved.<br>";
                $this->debug_to_console($response->json());

                $result = $response->json();
                //($result['data'] is an array of all photo objects. 
                foreach ($result['data'] as $photo) {
                    if (in_array($photo['id'], $ids)) {
                        // echo "already found.";
                        continue;
                    }

                    $ids[] = $photo['id']; //same as array.push() but php has another weird method to do it
                    InstagramPhoto::refresh($photo); //this will make some updates to dB.
                    //so existing photos don't get deleted, only new ones get added to dB
                    //afterwards only dB is queried on homepage and not instagram's server.
                }


                $next_url = array_get($result, 'pagination.next_url');
            } else {
                //echo "Something bad happened, insta doesn't like us!";
                //return Redirect::action('Admin\InstagramPhotosController@getIndex')
                    //->with('fail', 'Something went wrong :(');
                //no need to catch this errors are handled by laravel automatically.
                $next_url = null;
            }

        } while (isset($next_url));

        //echo "Database updated with new content (if any).<br>";
        return Redirect::action('Admin\InstagramPhotosController@getIndex')
            ->with('success', 'Instagram has been successfully synced');
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
