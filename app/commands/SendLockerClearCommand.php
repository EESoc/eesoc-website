<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class SendLockerClearCommand extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'locker:send_clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send end-of-year locker clear email to all owners.';

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
            if ( ! $user->lockers()->exists()) {
                continue;
            }
	
			$locker_name = "";
			
			foreach ($user->lockers() as $locker){
				$locker_name .= $locker->name." ";
				
			}
			
			$locker_name = trim($locker_name);
			
			
            $this->info(sprintf(
                '`%s` is an owner for locker `%s`.',
                $user->username, $locker_name
            ));

            if ( ! $this->option('pretend')) {
                Notification::sendLockerClear($user);
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
