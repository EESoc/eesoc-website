<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class SyncEActivitiesSalesCommand extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'eactivities:sales:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync eActivities Sales.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        $this->info('Warning: This feature is U/C...');


        //new DateTime('2000-01-01');
        //echo ;
        //return;

        $eactivities_client = new EActivities\Client( new Guzzle\Http\Client);


        $this->syncProduct($eactivities_client, Product::ID_EESOC_LOCKER);

        //for debugging
        //$this->info(print_r($eactivities_client->getProductList()));
        return; /*END OF CODE*/
        

        // @todo make a ask prompt for this.
        // ['1725', '1772', '1772-3']
        // $purchases = $eactivities_client->getPurchasesList(['1725', '1772', '1772-3'], 1983);
        // $purchases = $eactivities_client->getPurchasesList(['1725', '1772'], 20226);

        // Lockers in 2015/16 have product ID 13472. This is also defined in app/models/Product.php,
        // but who knows if this is accessible here.
        // @TODO; Software engineering... 
        $this->syncProduct($eactivities_client, Product::ID_EESOC_LOCKER);

       # Sync dinner sales 2015/16
        $this->syncProduct($eactivities_client, Product::ID_EESOC_DINNER, function($purchase, $sale, $user)
        {
            $dSale           = new DinnerSale;
            $dSale->user_id  = $user->id;
            $dSale->quantity = $purchase['quantity'];
            $dSale->origin   = "EActivities";
            $dSale->sale_id  = $sale->id;
            $dSale->save();
        });
    }

    protected function syncProduct($eactivities_client, $productId, $newCallback = NULL)
    {
        $product_info = $eactivities_client->getProductInfo($productId);
        $purchases = $eactivities_client->getPurchasesList($productId);

        if (array_key_exists('error', $product_info) || array_key_exists('error', $purchases)){
            $this->error("An error occurred, see dump below:\n");
            $this->info("\$product_info =>");
            print_r($product_info);
            $this->info("\$purchases =>");
            print_r($purchases);
            $this->error("Failed to sync product with ID {$productId}, terminating...");
            return;
        }
        
        //debug only
        /*$this->info("Current Product: " . $product_info['Name']); 
        $this->info(print_r($purchases));*/
        
        //syncronise each sale for given product
        foreach ($purchases as $purchase) {
            $purchase_date_array = preg_split('/-/', $purchase['SaleDateTime']);
            $year_code = ((int) $purchase_date_array[1] >= 9) ? sprintf( '%2d-%2d', (int) ($purchase_date_array[0][2] . $purchase_date_array[0][3]), (int) ($purchase_date_array[0][2] . $purchase_date_array[0][3]) + 1) : sprintf( '%2d-%2d', (int) ($purchase_date_array[0][2] . $purchase_date_array[0][3]) - 1, (int) ($purchase_date_array[0][2] . $purchase_date_array[0][3]));
            
            //debug only
            //$this->info(print_r($purchase['OrderNumber'] . " --- " . $purchase['SaleDateTime'] . " --- " . $purchase_date_array[1] . " --- " . $year_code));

            $sale = Sale::find($purchase['OrderNumber']);
            $new  = FALSE;

            //If not in EESoc dB, create new sale record
            if (!$sale) {
                $sale = new Sale;
                $sale->id = $purchase['OrderNumber'];
                $new = TRUE;
            }

           
            $user = User::where('username', '=', $purchase['Customer']['Login'])->first();
            
            //If not in EESoc dB, create new user record
            if (!$user) {
                $user = new User;
                $user->username = $purchase['Customer']['Login'];
                $user->cid      = $purchase['Customer']['CID'];
                $user->name     = "{$purchase['Customer']['FirstName']} {$purchase['Customer']['Surname']}";
                $user->email    = $purchase['Customer']['Email'];
                $user->save();
            }

            $sale->user()->associate($user); //Create link using foreign key, sets user_id field

            //Add remaining fields in sale record
            //API doesn't return year code so need to use our own calculated value
            $sale->year         = $year_code;
            $sale->product_name = $product_info['Name'];
            $sale->date         = $purchase['SaleDateTime'];
            $sale->quantity     = $purchase['Quantity'];
            $sale->unit_price   = $purchase['Price'];
            $sale->product_id   = $purchase['ProductID'];
            $sale->cid          = $purchase['Customer']['CID'];
            $sale->username     = $purchase['Customer']['Login'];
            $sale->email        = $purchase['Customer']['Email'];
            $sale->first_name   = $purchase['Customer']['FirstName'];
			$sale->last_name    = $purchase['Customer']['Surname'];

            $sale->save();

            if ($new && $newCallback)
                $newCallback($purchase, $sale, $user);
        }

        $this->info(sprintf('Successfully refreshed `%d` sale entries for product `%s`', count($purchases), $product_info['Name']));
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return array();
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return array();
    }

}
