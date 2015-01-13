<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class SendEmailsCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'emails:dinner:send';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Send queued emails.';

	/**
	 * Execute the console command.
	 *
	 * @return void
	 */
	public function fire()
	{
		// Mail
		$transport = Swift_MailTransport::newInstance();
		$html      = View::make('emails.dinner_confirmation.html')->render();
		$transport = Swift_MailTransport::newInstance();
		$mailer    = Swift_Mailer::newInstance($transport);

        // Why?
		$emails = array('dm1911@imperial.ac.uk', 'ck2211@imperial.ac.uk');

		foreach ($emails as $email)
		{
			$this->info(sprintf('Sending email `%s` to `%s`', 'EESoc Dinner Confirmation', $email));
			$message = Swift_Message::newInstance();

			$message->setFrom(array('dinner@eesoc.com' => 'EESoc'));
			$message->setReplyTo('dinner@eesoc.com');
			$message->setTo($email);
			$message->setSubject('EESoc Dinner Confirmation');
			$message->setBody($html, 'text/html');

			if ($mailer->send($message)) {
				$this->info(sprintf('Successfully sent email `%s` to `%s`', 'EESoc Dinner Confirmation', $email));
			} else {
				$this->error(sprintf('Something went wrong while sending email `%s` to `%s`', 'EESoc Dinner Confirmation', $email));
			}
		}
	}

    /* This may not function. There was a lot of commented code which I have attempted to preserve here for future use. */
	public function fireQueue()
	{
		$count = NewsletterEmailQueue::count();

		if ($count === 0) {
		    $this->info('There are no emails to send');
		    return;
		}

		if (!$this->confirm(sprintf('There\'s %d emails in the send queue. Are you sure you want to send them out now? [yes|no]', $count), true)) {
            return;
		}

		// Mail
		$transport = Swift_MailTransport::newInstance();
		$email = NewsletterEmail::findOrFail(1);

		$html = View::make('emails.dinner_confirmation.html')
			->with('body', $email->body)
			->render();

		$body = (new \TijsVerkoyen\CssToInlineStyles\CssToInlineStyles($html, file_get_contents(base_path() . '/public/assets/css/email.css')))
			->convert();

		$transport = Swift_MailTransport::newInstance();
		$mailer    = Swift_Mailer::newInstance($transport);
		$emails    = User::hasStudentGroup()->get()->lists('email');

		foreach ($emails as $email)
		{
			$this->info(sprintf('Sending email `%s` to `%s`', 'EESoc Dinner Confirmation', $email));
			$message = Swift_Message::newInstance();

			$message->setFrom(array('dinner@eesoc.com' => 'EESoc'));
			$message->setReplyTo('dinner@eesoc.com');
			$message->setTo($email);
			$message->setSubject('EESoc Dinner Confirmation');
			$message->setBody($html, 'text/html');

            if (isset($plaintext))
                $message->addPart($plaintext, 'text/plain');

			if ($mailer->send($message)) {
				$this->info(sprintf('Successfully sent email `%s` to `%s`', 'EESoc Dinner Confirmation', $email));

                // Remove from queue
				$queue->delete();
			} else {
				$this->error(sprintf('Something went wrong while sending email `%s` to `%s`', 'EESoc Dinner Confirmation', $email));
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
