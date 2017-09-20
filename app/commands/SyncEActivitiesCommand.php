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
        $this->info('addWarning: This is a broken feature, will be fixed soon.');
        //$username = $this->ask('What is your Imperial College login?');
        //$password = $this->secret('Password?');
        
        //api key can be stored and retrieved from config file

        //$credentials = new ImperialCollegeCredential($username, $password);

        $http_client = new Guzzle\Http\Client;
        //$http_client->setSslVerification(false);

        $eactivities_client = new EActivities\Client($http_client);

        /*//for debugging
        $resp = $eactivities_client->getBasicInfo();
        if(array_key_exists("error", $resp)){
            $this->info(var_dump($resp));
            $this->error("Some errors occurred, cannot continue with function.");
            return;
        }*/


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
