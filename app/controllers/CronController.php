<?php

class CronController extends BaseController {

    public function __construct()
    {
        // Skip CSRF filter

        // Set no time limit
        set_time_limit(0);
    }

    public function getInstagram()
    {
        $ids = array();
        $next_url = null;

        $client = new Guzzle\Http\Client('https://api.instagram.com/{version}', array(
            'version' => 'v1',
            'tag_name' => 'eesoc',
            'request.options' => array(
                'query' => array(
                    'client_id' => Config::get('instagram.client_id'),
                    'access_token' => Config::get('instagram.access_token'),
                ),
            ),
        ));

        do {
            if (isset($next_url)) {
                $next_url_params = parse_url($next_url);
                $request = $client->get($next_url_params['path'] . '?' . $next_url_params['query']);
            } else {
                $request = $client->get('tags/{tag_name}/media/recent');
            }

            $response = $request->send();

            if ($response->isSuccessful()) {
                $result = $response->json();
                foreach ($result['data'] as $photo) {
                    if (in_array($photo['id'], $ids)) {
                        continue;
                    }

                    $ids[] = $photo['id'];
                    InstagramPhoto::refresh($photo);
                }


                $next_url = array_get($result, 'pagination.next_url');
            } else {
                $next_url = null;
            }

        } while (isset($next_url));
    }

    public function postSales()
    {
        $username = Input::get('username');

        if (!User::where('username', '=', $username)->firstOrFail()->is_admin) {
            return Response::json(['success' => false, 'message' => 'You are not an admin!']);
        }

        $credentials = new ImperialCollegeCredential($username, Input::get('password'));

        $http_client = new Guzzle\Http\Client;
        $http_client->setSslVerification(false);

        $eactivities_client = new EActivities\Client($http_client);

        if (!$eactivities_client->signIn($credentials)) {
            return Response::json(['success' => false, 'message' => 'Error signing in! Please check your username and password.']);
        }

        //$eactivities_client->changeRole(Input::get('role_key'));

        $purchases = array_merge(
            $eactivities_client->getPurchasesList(Product::ID_EESOC_LOCKER), // EESoc Locker
            $eactivities_client->getPurchasesList(Product::ID_EESOC_DINNER) // EESoc Dinner
        );

        $emails_sent = 0;

        foreach ($purchases as $purchase) {

            //eAcivities Update 2017 temp fix
            $purchase['cid'] = $purchase['c_id/_card_number'];
            $purchase['last_name'] = $purchase['surname'];

            $sale = Sale::find($purchase['order_no']);

            if (!$sale) {
                $sale = new Sale;
                $sale->id = $purchase['order_no'];
                $sale->notified = false;
            }

            $user = User::where('username', '=', $purchase['login'])->first();
            if (!$user) {
                $user = new User;
                $user->username = $purchase['login'];
                $user->cid      = $purchase['cid'];
                $user->name     = "{$purchase['first_name']} {$purchase['last_name']}";
                $user->email    = $purchase['email'];
                $user->save();
            }

            $sale->user()->associate($user);

            foreach (['year', 'date', 'cid', 'first_name', 'last_name', 'email', 'product_name', 'product_id', 'quantity', 'unit_price', 'gross_price'] as $attribute) {
                $sale->{$attribute} = $purchase[$attribute];
            }
            $sale->username = $purchase['login'];
            $sale->save();

            // Is a locker sale and not notified
            if ($sale->product_id === Product::ID_EESOC_LOCKER && !$sale->notified) {
                if (Notification::sendLockerInformation($user)) {
                    $emails_sent++;
                    $sale->notified = true;
                    $sale->save();
                }
            }

            // Is a dinner sale
            if ($sale->product_id === Product::ID_EESOC_DINNER) {
                if (!DinnerSale::where('sale_id', '=', $sale->id)->exists()) {
                    $dinner_sale = new DinnerSale;
                    $dinner_sale->user()->associate($user);
                    $dinner_sale->quantity = $sale->quantity;
                    $dinner_sale->origin = 'eactivities';
                    $dinner_sale->sale()->associate($sale);
                    $dinner_sale->save();
                }
            }
        }

        return Response::json([
            'success' => true,
            'message' => sprintf('Successfully refreshed %d sale entries. Sent %d emails.', count($purchases), $emails_sent),
        ]);
    }

}
