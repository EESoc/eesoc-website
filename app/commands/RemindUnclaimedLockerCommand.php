<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class RemindUnclaimedLockerCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'locker:remind';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Remind users with unclaimed locker(s).';

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
		// @todo refactor
		$users = User::all();

		foreach ($users as $user) {
			if ( ! $user->has_unclaimed_lockers) {
				continue;
			}

			$this->info(sprintf(
				'`%s` has %d %s',
				$user->username,
				$user->unclaimed_lockers_count,
				Str::plural('unclaimed locker', $user->unclaimed_lockers_count)
			));

			if ( ! $this->option('pretend')) {
				Notification::sendLockerClaimReminder($user);
			}
		}
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array(
			array('pretend', 'p', InputOption::VALUE_NONE, 'Lists affected users without sending out emails.'),
		);
	}

}