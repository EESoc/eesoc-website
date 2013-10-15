<?php

class Notification {

	public static function sendLockerInformation(User $user)
	{
		$transport = Swift_MailTransport::newInstance();
		$mailer = Swift_Mailer::newInstance($transport);
		$message = Swift_Message::newInstance();

		$message->setFrom(array('please-reply@eesoc.com' => 'EESoc'));
		$message->setReplyTo('please-reply@eesoc.com');
		$message->setTo($user->email);
		$message->setSubject('Locker Ready To Be Claimed');

		$html = View::make('emails.locker_notification')
			->with('user', $user)
			->render();
		$message->setBody($html, 'text/html');
		$message->addPart('We have received your locker order. Your locker is now ready to be claimed.', 'text/plain');

		return $mailer->send($message);
	}

}