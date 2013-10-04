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

		// $transport = Swift_SmtpTransport::newInstance('localhost', 1025);
		// $transport = Swift_SmtpTransport::newInstance('smtp.gmail.com', 465, 'ssl')
		//   ->setUsername('')
		//   ->setPassword('');

		$email = NewsletterEmail::findOrFail(1);

		$html = View::make('email_layouts.basic')
			->with('body', $email->body)
			->render();

		$body = (new \TijsVerkoyen\CssToInlineStyles\CssToInlineStyles($html, file_get_contents(base_path() . '/public/assets/css/email.css')))
			->convert();

		$transport = Swift_MailTransport::newInstance();

		$mailer = Swift_Mailer::newInstance($transport);

		$emails = array('jianyuan@gmail.com', 'jian.lee11@imperial.ac.uk');

		foreach ($emails as $email)
		{
			$this->info(sprintf('Sending email `%s` to `%s`', 'A chilled end to the week', $email));
			$message = Swift_Message::newInstance();

			$message->setFrom(array('no-reply@eesoc.com' => 'EESoc'));
			$message->setTo($email);
			$message->setSubject('A chilled end to the week');
			$message->setBody($body, 'text/html');

			if ($mailer->send($message)) {
				$this->info(sprintf('Successfully sent email `%s` to `%s`', 'A chilled end to the week', $email));

				// Remove from queue
				// $queue->delete();
			} else {
				$this->error(sprintf('Something went wrong while sending email `%s` to `%s`', 'A chilled end to the week', $email));
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