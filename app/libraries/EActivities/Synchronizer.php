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
        $this->log('info', 'Downloading members list...');
        $members = $this->client->getMembersList();
        $this->log('info', 'Members list successfully downloaded.');

        if(array_key_exists("error", $members)){
            $this->info(var_dump($members));
            $this->error("Some errors occurred, cannot continue with function.");
            return;
        }

        //2017: Use latest JSON returned field-names: Login, Firstname, Surname, Email, CID

        // Reset membership status
        User::resetMemberships();
        $this->log('info', 'Resetted membership status');

        $this->log('info', print_r($members, true));

        foreach ($members as $member) {
            // Find or create
            $user = User::where('username', '=', $member['Login'])->first();
            if ( ! $user) {
                $user = new User;
                $user->username = $member['Login'];
            }

            $user->name      = "{$member['FirstName']} {$member['Surname']}";
            $user->email     = $member['Email'];
            $user->cid       = $member['CID'];
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
