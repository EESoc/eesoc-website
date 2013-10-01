<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class SyncEActivitiesCommand extends Command implements Loggable {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'eactivities:sync';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Sync with EActivities.';

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

		$this->info('Synchronizing members list');
		(new EActivities\Synchronizer($eactivities_client, true, $this))->perform();
		$this->info('Successfully synchronized members list');
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