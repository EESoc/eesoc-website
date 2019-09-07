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
        $this->info('SalesStat@' . date('H:i_d-m-y'));

        $eactivities_client = new EActivities\Client( new Guzzle\Http\Client);

        //for debugging
        // UNCOMMENT AND RUN THIS COMMAND IN TERMINAL TO SEE PROD_IDS
        // OR ELSE IF >1 SALES, FIND ID IN EACTIVITES UNION PAGE
        //$this->info(print_r($eactivities_client->getProductList()));

        // only sync products with well-defined IDs
        foreach (Product::is_product_syncable() as $prod_id => $is_syncable) {
            //$this->info('Product: ' . $prod_id . ' IsSyncable: ' . ((string) $is_syncable)); //for debugging
            if ($is_syncable == 1){
                if ($prod_id == Product::ID_EESOC_DINNER || $prod_id == Product::ID_EESOC_DINNER_NON_MEMBER){
                    // special case
                    $this->syncProduct($eactivities_client, $prod_id, 
                        function($purchase, $sale, $user){
                            //$this->info(sprintf('New Member/Non-member DinnerSale!'));
                            $dSale           = new DinnerSale;
                            $dSale->user_id  = $user->id;
                            $dSale->quantity = $purchase['Quantity'];
                            $dSale->origin   = "EActivities";
                            $dSale->sale_id  = $sale->id;
                            $dSale->save();
                        });
                }
                else {
                    $this->syncProduct($eactivities_client, $prod_id);
                }
            }
        }
        
        
        return; /*END OF CODE*/
       
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

        //Count existing quantity purchased BEFORE NEW SYNC
        $orig_count = Product::totalQuantity($productId);
        
        //syncronise each sale for given product
        foreach ($purchases as $purchase) {
            $purchase_date_array = preg_split('/-/', $purchase['SaleDateTime']);
            $year_code = ((int) $purchase_date_array[1] >= 9) ? sprintf( '%2d-%2d', (int) ($purchase_date_array[0][2] . $purchase_date_array[0][3]), (int) ($purchase_date_array[0][2] . $purchase_date_array[0][3]) + 1) : sprintf( '%2d-%2d', (int) ($purchase_date_array[0][2] . $purchase_date_array[0][3]) - 1, (int) ($purchase_date_array[0][2] . $purchase_date_array[0][3]));
            
            //debug only
            //$this->info(print_r($purchase['OrderNumber'] . " --- " . $purchase['SaleDateTime'] . " --- " . $purchase_date_array[1] . " --- " . $year_code));
            $count = Sale::where('order_number', '=', $purchase['OrderNumber'])
            ->where('product_id', '=', $purchase['ProductID'])->count();
            if ($count > 1) { $this->info("Duplicate entries, needed 1 found " . $count); }

            $sale = Sale::where('order_number', '=', $purchase['OrderNumber'])
                    ->where('product_id', '=', $purchase['ProductID'])->first();
            $new  = FALSE;



            //UNDETECTED FOR YEARS, RACE CONDITION CHECK
            //When 2 distinct products bought of same id
            //This should never run now
            if ($sale && $sale->product_id != $purchase['ProductID']){
                $this->info("SameOrder-DiffProd\tOriginal Prod: " . $sale->product_name . "\tNew Prod: " . $product_info['Name'] . "\tUsername: " . $sale->username . "\n");
            }


            //If not in EESoc dB, create new sale record
            if (!$sale) {
                $sale = new Sale;
                $new = TRUE;
            }

            

           
            $user = User::where('username', '=', $purchase['Customer']['Login'])->first();
            
            //If not in EESoc dB, create new user record -- need to fix this for people buying membership
            if (!$user) {
                $user = new User;
                $user->username = $purchase['Customer']['Login'];
                $user->cid      = $purchase['Customer']['CID'];
                $user->name     = "{$purchase['Customer']['FirstName']} {$purchase['Customer']['Surname']}";
                $user->email    = $purchase['Customer']['Email'];
                
                if ($productId == Product::ID_EESOC_MEMBERSHIP){
                    $user->is_member = true;
                }

                $user->save();
            }

            $sale->user()->associate($user); //Create link using foreign key, sets user_id field

            //Add remaining fields in sale record
            //API doesn't return year code so need to use our own calculated value
            $sale->year         = $year_code;
            $sale->product_name = $product_info['Name'];
            $sale->order_number = $purchase['OrderNumber']; //don't set id (it is auto) only order number!
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

        $this->info(sprintf("Product: %s\tPurchases: %d (%+d);", 
                    substr($product_info['Name'], 0, min(strlen($product_info['Name']), 12)), 
                    Product::totalQuantity($productId), 
                    (Product::totalQuantity($productId) - $orig_count))
                );
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
