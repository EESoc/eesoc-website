<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class SendLockerTermsAndConditionsCommand extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'locker:send_terms';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send locker terms and conditions to all owners.';

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

            $this->info(sprintf(
                '`%s` is an owner.',
                $user->username
            ));

            if ( ! $this->option('pretend')) {
                Notification::sendLockerTermsAndConditions($user);
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
