<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class BecomeAdminCommand extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'admin:become';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Become an Admin given an Imperial College Login.';

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
        $user = User::findOrCreateWithLDAP($this->argument('username'));

        if ( ! $user) {
            $this->error('That Imperial College Login does not exist!');
            return;
        }

        if ($this->confirm(sprintf('Are you sure you want to make `%s` an admin? [yes|no]', $user->username))) {
            $user->is_admin = true;
            $user->save();

            $this->info(sprintf('Successfully turned `%s` into an admin!', $user->username));
        } else {
            $this->error('Promotion cancelled.');
        }
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return array(
            array('username', InputArgument::REQUIRED, 'Imperial College Login.'),
        );
    }

}
