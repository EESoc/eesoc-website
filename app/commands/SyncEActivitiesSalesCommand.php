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
		$purchases = $eactivities_client->getPurchasesList(['1725', '1772'], 20226);

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
				$user->cid      = $purchase['cid'];
				$user->name     = "{$purchase['first_name']} {$purchase['last_name']}";
				$user->email    = $purchase['email'];
				$user->save();
			}

			$sale->user()->associate($user);

			foreach (['year', 'date', 'cid', 'first_name', 'last_name', 'email', 'product_name', 'quantity', 'unit_price', 'gross_price'] as $attribute) {
				$sale->{$attribute} = $purchase[$attribute];
			}
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