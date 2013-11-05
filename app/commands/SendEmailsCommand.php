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
	protected $name = 'emails:send';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Send queued emails.';

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
		// $count = NewsletterEmailQueue::count();

		// if ($count === 0) {
		// 	$this->info('There are no emails to send');
		// 	return;
		// }

		// if ( ! $this->confirm(sprintf('There\'s %d emails in the send queue. Are you sure you want to send them out now? [yes|no]', $count), true)) {
		// 	return;
		// }

		// Mail
		$transport = Swift_MailTransport::newInstance();
		// $transport = Swift_SmtpTransport::newInstance('localhost', 1025);
		// $transport = Swift_SmtpTransport::newInstance('smtp.gmail.com', 465, 'ssl')
		//   ->setUsername('')
		//   ->setPassword('');

		// $email = NewsletterEmail::findOrFail(1);

		$html = View::make('emails.2013_11_03.html')
			// ->with('body', $email->body)
			->render();
		$plaintext = View::make('emails.2013_11_03.plaintext')
			->render();

		// $body = (new \TijsVerkoyen\CssToInlineStyles\CssToInlineStyles($html, file_get_contents(base_path() . '/public/assets/css/email.css')))
		// 	->convert();

		$transport = Swift_MailTransport::newInstance();

		$mailer = Swift_Mailer::newInstance($transport);

		// $emails = array('jianyuan@gmail.com', 'jian.lee11@imperial.ac.uk');
		// $emails = array('darioml1911@gmail.com', 'dm1911@imperial.ac.uk');
		$emails = User::hasStudentGroup()->get()->lists('email');

		foreach ($emails as $email)
		{
			$this->info(sprintf('Sending email `%s` to `%s`', 'T minus One to your graduate Job - Pub Crawl TONIGHT', $email));
			$message = Swift_Message::newInstance();

			$message->setFrom(array('please-reply@eesoc.com' => 'EESoc'));
			$message->setReplyTo('please-reply@eesoc.com');
			$message->setTo($email);
			$message->setSubject('T minus One to your graduate Job - Pub Crawl TONIGHT');
			$message->setBody($html, 'text/html');
			$message->addPart($plaintext, 'text/plain');

			if ($mailer->send($message)) {
				$this->info(sprintf('Successfully sent email `%s` to `%s`', 'T minus One to your graduate Job - Pub Crawl TONIGHT', $email));

				// Remove from queue
				// $queue->delete();
			} else {
				$this->error(sprintf('Something went wrong while sending email `%s` to `%s`', 'T minus One to your graduate Job - Pub Crawl TONIGHT', $email));
			}

			unset($message);
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