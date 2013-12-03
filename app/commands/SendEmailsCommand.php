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

		$html = View::make('emails.xmas_confirmation.html')
			// ->with('body', $email->body)
			->render();

		// $body = (new \TijsVerkoyen\CssToInlineStyles\CssToInlineStyles($html, file_get_contents(base_path() . '/public/assets/css/email.css')))
		// 	->convert();

		$transport = Swift_MailTransport::newInstance();

		$mailer = Swift_Mailer::newInstance($transport);

		// $emails = array('jianyuan@gmail.com', 'jian.lee11@imperial.ac.uk');
		$emails = array('dm1911@imperial.ac.uk', 'ck2211@imperial.ac.uk');
		// $emails = User::hasStudentGroup()->get()->lists('email');
		// $staff = array('jyl111@imperial.ac.uk','ck2211@imperial.ac.uk','pl2311@imperial.ac.uk','txl11@imperial.ac.uk','yw3310@imperial.ac.uk','qc1112@imperial.ac.uk','cl3510@imperial.ac.uk','cgb10@imperial.ac.uk','as10512@imperial.ac.uk','dm1911@imperial.ac.uk','rmb209@imperial.ac.uk','nlb113@imperial.ac.uk','xl5512@imperial.ac.uk','zg1213@imperial.ac.uk','es113@imperial.ac.uk','pva13@imperial.ac.uk','ch3810@imperial.ac.uk','jd1611@imperial.ac.uk','wy1111@imperial.ac.uk','nm1810@imperial.ac.uk','si1509@imperial.ac.uk','jh3711@imperial.ac.uk','rw2010@imperial.ac.uk','lfj10@imperial.ac.uk','sp4713@imperial.ac.uk','nathan.warner13@imperial.ac.uk','pm2613@imperial.ac.uk','pm1113@imperial.ac.uk','by1413@imperial.ac.uk','vlad-petru.veigang-radulescu13@imperial.ac.uk','mm5213@imperial.ac.uk','ah4412@imperial.ac.uk','hr912@imperial.ac.uk','aoe111@imperial.ac.uk','pk1313@imperial.ac.uk','tp709@imperial.ac.uk','vc1213@imperial.ac.uk','yw4311@imperial.ac.uk','hm2211@imperial.ac.uk','jc4913@imperial.ac.uk','sg3510@imperial.ac.uk','rww112@imperial.ac.uk','sav112@imperial.ac.uk','hgp10@imperial.ac.uk','cwh111@imperial.ac.uk','nm2213@imperial.ac.uk','mm5110@imperial.ac.uk','md2610@imperial.ac.uk','sa5210@imperial.ac.uk','aes110@imperial.ac.uk','ykc111@imperial.ac.uk','ak6711@imperial.ac.uk','lz3011@imperial.ac.uk','amm213@imperial.ac.uk','shl213@imperial.ac.uk','yl7011@imperial.ac.uk','tz1010@imperial.ac.uk','dm2913@imperial.ac.uk','ka2611@imperial.ac.uk','ilb10@imperial.ac.uk','ap6311@imperial.ac.uk','yh4311@imperial.ac.uk','arh113@imperial.ac.uk','mks211@imperial.ac.uk','zg1011@imperial.ac.uk','ycl13@imperial.ac.uk','pe313@imperial.ac.uk','yy4111@imperial.ac.uk','ahmed.elbessoumy13@imperial.ac.uk','chinemelu.ezeh10@imperial.ac.uk','dario@imperial.ac.uk','xyg11@imperial.ac.uk','pl2311@imperial.ac.uk','rew09@imperial.ac.uk','aa12311@imperial.ac.uk','ly1211@imperial.ac.uk','lk811@imperial.ac.uk','af1312@imperial.ac.uk','jf1711@imperial.ac.uk','jv510@imperial.ac.uk','lz2411@imperial.ac.uk','cc3810@imperial.ac.uk','lma211@imperial.ac.uk','hs2311@imperial.ac.uk','bd411@imperial.ac.uk','mjs311@imperial.ac.uk','sgk13@imperial.ac.uk','cp510@imperial.ac.uk','ev613@imperial.ac.uk','fs1910@imperial.ac.uk','sh4311@imperial.ac.uk','pa1412@imperial.ac.uk','xz7813@imperial.ac.uk','gk1112@imperial.ac.uk','yl11610@imperial.ac.uk','gp1110@imperial.ac.uk','dy1512@imperial.ac.uk','dna112@imperial.ac.uk','br812@imperial.ac.uk','yk1711@imperial.ac.uk','kxh@imperial.ac.uk','se1112@imperial.ac.uk','erh111@imperial.ac.uk','dk2112@imperial.ac.uk','bl1711@imperial.ac.uk','dl2012@imperial.ac.uk','mm5312@imperial.ac.uk','dt10@imperial.ac.uk','fobelets@imperial.ac.uk','ydl10@imperial.ac.uk','jsd111@imperial.ac.uk','sac111@imperial.ac.uk','kkg11@imperial.ac.uk','ds4111@imperial.ac.uk','pd111@imperial.ac.uk','um313@imperial.ac.uk','cc4611@imperial.ac.uk','ym1310@imperial.ac.uk','dt10@imperial.ac.uk','bw1313@imperial.ac.uk','rs6713@imperial.ac.uk','kql10@imperial.ac.uk','pld@imperial.ac.uk','wt211@imperial.ac.uk','sb4313@imperial.ac.uk','lni12@imperial.ac.uk','spr13@imperial.ac.uk','yl13113@imperial.ac.uk','au411@imperial.ac.uk','lg1210@imperial.ac.uk','rb2411@imperial.ac.uk','ajw10@imperial.ac.uk','phg11@imperial.ac.uk','ria12@imperial.ac.uk','pcm13@imperial.ac.uk','sharlyn.doshi13@imperial.ac.uk','dcc10@imperial.ac.uk','maryam.ibrahim13@imperial.ac.uk','swk111@imperial.ac.uk','wnl111@imperial.ac.uk','txl11@imperial.ac.uk','rr2011@imperial.ac.uk','jr2812@imperial.ac.uk','jrf111@imperial.ac.uk','iw411@imperial.ac.uk','cyl312@imperial.ac.uk','mz3112@imperial.ac.uk','ojf12@imperial.ac.uk','lt1310@imperial.ac.uk','af2213@imperial.ac.uk','vlg13@imperial.ac.uk','ajg12@imperial.ac.uk','tcg@imperial.ac.uk','aan11@imperial.ac.uk','rr2211@imperial.ac.uk','df611@imperial.ac.uk','cjw111@imperial.ac.uk','vjk10@imperial.ac.uk','ak7213@imperial.ac.uk','sp3613@imperial.ac.uk','cedr@imperial.ac.uk','erh111@imperial.ac.uk','fengchu.zhang13@imperial.ac.uk','cl3510@imperial.ac.uk','cp2011@imperial.ac.uk','zz1012@imperial.ac.uk','xj413@imperial.ac.uk','dh413@imperial.ac.uk','oh13@imperial.ac.uk','qz1310@imperial.ac.uk','dh413@imperial.ac.uk','sw1513@imperial.ac.uk','lb113@imperial.ac.uk','zc1213@imperial.ac.uk','yj1113@imperial.ac.uk','xl5313@imperial.ac.uk','bf710@imperial.ac.uk','zz613@imperial.ac.uk','cz613@imperial.ac.uk','diyar.alyasiri13@imperial.ac.uk','hrobens@imperial.ac.uk','sv1813@imperial.ac.uk','lior.luthi10@imperial.ac.uk','nag113@imperial.ac.uk','cz813@imperial.ac.uk','eusebius.ngemera13@imperial.ac.uk', 'p.cheung@imperial.ac.uk');

		foreach ($emails as $email)
		{
			$this->info(sprintf('Sending email `%s` to `%s`', 'EESoc Christmas Dinner Confirmation', $email));
			$message = Swift_Message::newInstance();

			$message->setFrom(array('dinner@eesoc.com' => 'EESoc'));
			$message->setReplyTo('dinner@eesoc.com');
			$message->setTo($email);
			$message->setSubject('EESoc Christmas Dinner Confirmation');
			$message->setBody($html, 'text/html');
			//$message->addPart($plaintext, 'text/plain');

			if ($mailer->send($message)) {
				$this->info(sprintf('Successfully sent email `%s` to `%s`', 'EESoc Christmas Dinner Confirmation', $email));

				// Remove from queue
				// $queue->delete();
			} else {
				$this->error(sprintf('Something went wrong while sending email `%s` to `%s`', 'EESoc Christmas Dinner Confirmation', $email));
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