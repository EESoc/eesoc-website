<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class SendDinnerEmailsCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'dinner:emails:send';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Sends e-mails for the Dinner.';

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

		$subject = 'EESoc Dinner Dietary Requirements';

		$sales = DinnerSale::where('notified', '=', false)->get();
		foreach ($sales as $sale) {
			$message->setFrom(array('eesoc@imperial.ac.uk' => 'EESoc'));
			$message->setReplyTo('eesoc.webmaster@imperial.ac.uk');
			$message->setTo($sale->user->email);
			$message->setSubject($subject);

			if ($sale->quantity > 1) {
				$template = 'emails.dinner.diet_multiple';
			} else {
				$template = 'emails.dinner.diet_single';
			}

			$html = View::make($template)
				->with('subject', $subject)
				->with('sale', $sale)
				->render();

			$message->setBody($html, 'text/html');
			$message->addPart('EESoc Dinner Dietary Requirements. Please read!', 'text/plain');

			if ($mailer->send($message)) {
				$sale->notified = true;
				$sale->save();
			}
		}
	}

}
