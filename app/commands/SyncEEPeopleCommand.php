<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class SyncEEPeopleCommand extends Command implements Loggable {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'eepeople:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync with EEPeople.';

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

        if (App::environment() === 'local') {
            $http_client->setSslVerification(false);
        }

        $eepeople_client = new EEPeople\Client($http_client);

        if ( ! $eepeople_client->signIn($credentials)) {
            $this->error('Error signing in! Please check your username and password.');
            return;
        }

        $synchronizer = new EEPeople\Synchronizer($eepeople_client, true, $this->option('skip'), $this);
        $synchronizer->perform();
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
        return array(
            array('skip', null, InputOption::VALUE_NONE, 'Skip populated users.', null),
        );
    }

}
