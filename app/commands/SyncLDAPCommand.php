<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class SyncLDAPCommand extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'ldap:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync users with LDAP.';

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
        $users = User::all();
        foreach ($users as $user) {
            if ($user->synchronizeWithLDAP()) {
                $this->info(sprintf('Synced with `%s`', $user->name));
            } else {
                $this->error(sprintf('Error synchronizing with `%s`', $user->name));
            }
        }
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
