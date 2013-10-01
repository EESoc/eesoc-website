<?php
namespace EActivities;

use \Loggable;
use \User;

class Synchronizer {

	public function __construct(Client $client, $no_time_limit = true, Loggable $logger = null)
	{
		$this->client = $client;

		if ($no_time_limit) {
			set_time_limit(0);
		}

		$this->logger = $logger;
	}

	public function perform()
	{
		$this->log('info', 'Downloading members list');
		$members = $this->client->getMembersList();
		$this->log('info', 'Members list successfully downloaded');

		// Reset membership status
		User::resetMemberships();
		$this->log('info', 'Resetted membership status');

		foreach ($members as $member) {
			// Find or create
			$user = User::where('username', '=', $member['login'])->first();
			if ( ! $user) {
				$user = new User;
				$user->username = $member['login'];
			}

			$user->name      = "{$member['first_name']} {$member['last_name']}";
			$user->email     = $member['email'];
			$user->cid       = $member['cid'];
			$user->is_member = true;
			$user->save();
			
			$this->log('info', sprintf('Updated user `%s`', $user->name));
		}
	}

	private function log($type, $message)
	{
		if ($this->logger) {
			$this->logger->{$type}($message);
		}
	}

}