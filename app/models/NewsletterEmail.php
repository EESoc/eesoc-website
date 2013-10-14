<?php

class NewsletterEmail extends Eloquent {

	protected $fillable = ['subject', 'preheader', 'from_name', 'from_email', 'reply_to_email', 'body'];

	public function newsletters()
	{
		return $this->belongsToMany('Newsletter', 'newsletters_newsletter_emails');
	}

	public function queuedEmails()
	{
		return $this->hasMany('NewsletterEmailQueue');
	}

	public function getSentCountAttribute()
	{
		return $this->queuedEmails()->sent()->count();
	}

	public function getSendQueueLengthAttribute()
	{
		return $this->queuedEmails()->count();
	}

	public function getIsDraftAttribute()
	{
		return $this->state === 'draft';
	}

	public function getIsSendingAttribute()
	{
		return $this->state === 'sending';
	}

	public function getIsCompletedAttribute()
	{
		return $this->state === 'completed';
	}

	public function getCanSaveAttribute()
	{
		return $this->state === 'draft';
	}

	public function getCanSendAttribute()
	{
		return $this->state === 'draft';
	}

	public function getCanPauseAttribute()
	{
		return $this->state === 'sending';
	}

	public function getSentEmailPercentageAttribute()
	{
		$total = $this->send_queue_length;

		if ($total == 0) {
			return 0;
		}

		$sent = $this->sent_count;

		return ceil($sent / $total * 100);
	}

	/**
	 * Builds an email queue
	 */
	public function buildEmailQueue()
	{
		set_time_limit(0);

		$inserts = [];

		foreach ($this->newsletters as $newsletter) {
			$student_group_ids = $newsletter->student_groups->lists('id');
			if ( ! empty($student_group_ids)) {
				$users = User::whereIn('student_group_id', $student_group_ids)->get();
				foreach ($users as $user) {
					if ( ! $user->email) {
						continue;
					}

					$inserts[] = [
						'newsletter_email_id' => $this->id,
						'tracker_token'       => str_random(20),
						'to_email'            => $user->email,
						'user_id'             => $user->id,
					];
				}
			}

			$query = $newsletter->subscriptions();

			if ( ! empty($student_group_ids)) {
				$query
					->whereNull('user_id')
					->orWhereNotIn('user_id', function($query) use ($student_group_ids) {
						return $query
							->select('id')
							->from('users')
							->whereIn('student_group_id', $student_group_ids);
					});
			}

			$subscribers = $query->get();
			foreach ($subscribers as $subscriber) {
				if ( ! $subscriber->email) {
					continue;
				}

				$inserts[] = [
					'newsletter_email_id' => $this->id,
					'tracker_token'       => str_random(20),
					'to_email'            => $subscriber->email,
					'user_id'             => null,
				];
			}
		}

		DB::table('newsletter_email_queue')->insert($inserts);
	}

	/**
	 * Send batch emails
	 */
	public function sendBatch($size = 10)
	{
		if (App::environment() === 'local') {
			// Mailcatcher
			$transport = Swift_SmtpTransport::newInstance('localhost', 1025);
		} else {
			$transport = Swift_MailTransport::newInstance();
		}

		$mailer = Swift_Mailer::newInstance($transport);

		$message = Swift_Message::newInstance();
		$message->setFrom([$this->from_email => $this->from_name]);
		if ($this->reply_to_email) {
			$message->setReplyTo($this->reply_to_email);
		}
		$message->setSubject($this->subject);
		$message->setBody($this->body, 'text/html');
		if ($this->preheader) {
			$message->addPart($this->preheader, 'text/plain');
		}

		$recipients = $this->queuedEmails()->pendingSend()->take($size)->get();
		if ($recipients->isEmpty()) {
			$this->state = 'completed';
			$this->save();
		} else {
			foreach ($recipients as $recipient) {
				$message->setTo($recipient->to_email);

				// @todo move this to template
				$message->setBody(
					$message->getBody()
					.
					'<img src="' . $recipient->tracking_pixel_url . '" width="1" height="1" />'
				);

				if ($mailer->send($message)) {
					$recipient->sent = true;
					$recipient->save();
				}
			}
		}
	}

}