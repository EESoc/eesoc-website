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
        $username = $this->ask('What is your Imperial College login?');
        $password = $this->secret('Password?');

        $credentials = new ImperialCollegeCredential($username, $password);

        $http_client = new Guzzle\Http\Client;
        $http_client->setSslVerification(false);

        $eactivities_client = new EActivities\Client($http_client);

        if ( ! $eactivities_client->signIn($credentials)) {
            $this->error('Error signing in! Please check your username and password.');
            return;
        }

        // Role changing
        while (true) {
            $roles = $eactivities_client->getCurrentAndOtherRoles();
            $this->info(sprintf('Your current role is `%s`', $roles['current']));

            if ($this->confirm('Continue with this role? [yes|no]')) {
                break;
            }

            $this->info('Other roles:');
            foreach ($roles['others'] as $role_key => $role) {
                $this->info(sprintf('[%d] %s', $role_key, $role));
            }

            while (true) {
                $role_key = $this->ask('Enter role key:');

                if (ctype_digit($role_key) && isset($roles['others'][(int) $role_key])) {
                    break;
                } else {
                    $this->error('Role key does not exist, please try again');
                }
            }

            $eactivities_client->changeRole($role_key);
        }

        // @todo make a ask prompt for this.
        // ['1725', '1772', '1772-3']
        // $purchases = $eactivities_client->getPurchasesList(['1725', '1772', '1772-3'], 1983);
        // $purchases = $eactivities_client->getPurchasesList(['1725', '1772'], 20226);

        // Lockers in 2014/15 have product ID 8757. This is also defined in app/models/Product.php,
        // but who knows if this is accessible here.
        // @TODO; Software engineering
        $this->syncProduct($eactivities_client, 8757);
        $this->syncProduct($eactivities_client, 9572);
    }

    protected function syncProduct($eactivities_client, $productId)
    {
        $purchases = $eactivities_client->getPurchasesList($productId);

        foreach ($purchases as $purchase) {
            $sale = Sale::find($purchase['order_no']);
            if ( ! $sale) {
                $sale = new Sale;
                $sale->id = $purchase['order_no'];
            }

            $user = User::where('username', '=', $purchase['login'])->first();
            if ( ! $user) {
                $user = new User;
                $user->username = $purchase['login'];
                $user->cid      = $purchase['c_id/_card_number'];
                $user->name     = "{$purchase['first_name']} {$purchase['last_name']}";
                $user->email    = $purchase['email'];
                $user->save();
            }

            $sale->user()->associate($user);

            foreach (['year',
                  'date',
                  'first_name',
                  'last_name',
                  'email',
                  'product_name',
                  'quantity',
                  'unit_price',
                  'gross_price',
                  'product_id'] as $attribute) {
                $sale->{$attribute} = $purchase[$attribute];
            }

            /* The CSV has the field 'CID/Card Number'. By some magic, this is converted by the parser
             * to the string below. */
            $sale->cid      = $purchase['c_id/_card_number'];
            $sale->username = $purchase['login'];
            $sale->save();
        }

        $this->info(sprintf('Successfully refreshed `%d` sale entries', count($purchases)));
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
