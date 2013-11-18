<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class SendXmasDinnerEmailsCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'xmas:emails:send';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Command description.';

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
		$transport = Swift_MailTransport::newInstance();
		$mailer = Swift_Mailer::newInstance($transport);
		$message = Swift_Message::newInstance();

		$subject = 'Christmas Dinner Dietary Requirements';

		$sales = ChristmasDinnerSale::where('notified', '=', false)->get();
		foreach ($sales as $sale) {
			$message->setFrom(array('eesoc@imperial.ac.uk' => 'EESoc'));
			$message->setReplyTo('ck2211@imperial.ac.uk');
			$message->setTo($sale->user->email);
			$message->setSubject($subject);

			if ($sale->quantity > 1) {
				$template = 'emails.xmas.diet_multiple';
			} else {
				$template = 'emails.xmas.diet_single';
			}

			$html = View::make($template)
				->with('subject', $subject)
				->with('sale', $sale)
				->render();

			$message->setBody($html, 'text/html');
			$message->addPart('Christmas Dinner Dietary Requirements. Please read!', 'text/plain');

			if ($mailer->send($message)) {
				$sale->notified = true;
				$sale->save();
			}
		}
	}

}