<?php

class NewsletterEmail extends Eloquent {

	protected $fillable = ['subject', 'preheader', 'from_name', 'from_email', 'reply_to_email', 'body'];

	public function __construct()
	{
		parent::__construct();

		$this->state = 'draft';
	}

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
			// Process student groups
			$student_group_ids = $newsletter->student_groups->lists('id');
			if ( ! empty($student_group_ids)) {
				$users = User::whereIn('student_group_id', $student_group_ids)
					->whereNotIn('id', function($query) {
						// Remove duplicate
						return $query
							->select('user_id')
							->from('newsletter_email_queue')
							->where('newsletter_email_id', '=', $this->id);
					})
					->get();
				foreach ($users as $user) {
					$inserts[] = [
						'newsletter_email_id' => $this->id,
						'tracker_token'       => str_random(20),
						'to_email'            => $user->email,
						'user_id'             => $user->id,
					];
				}
			}

			// Process normal email subscriptions
			$subscribers_query = $newsletter
				->subscriptions()
				->whereNotIn('email', function($query) {
					// Remove duplicate
					return $query
						->select('to_email')
						->from('newsletter_email_queue')
						->where('newsletter_email_id', '=', $this->id);
				});

			if ( ! empty($student_group_ids)) {
				// Remove duplicate
				$subscribers_query
					->whereNull('user_id')
					->orWhereNotIn('user_id', function($query) use ($student_group_ids) {
						return $query
							->select('id')
							->from('users')
							->whereIn('student_group_id', $student_group_ids);
					});
			}

			// Get subscribers
			$subscribers = $subscribers_query->get();
			foreach ($subscribers as $subscriber) {
				$inserts[] = [
					'newsletter_email_id' => $this->id,
					'tracker_token'       => str_random(20),
					'to_email'            => $subscriber->email,
					'user_id'             => null,
				];
			}
		}

		if ( ! empty($inserts)) {
			DB::table('newsletter_email_queue')->insert($inserts);
		}
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

		$message = $this->buildMessage();

		$recipients = $this
			->queuedEmails()
			->pendingSend()
			->take($size)
			->get();

		$sent_emails = [];

		if ($recipients->isEmpty()) { // Finished sending out emails
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
					// Mark email queue as sent
					$recipient->sent = true;
					$recipient->save();
				}

				$sent_emails[] = $recipient->to_email;
			}
		}

		return $sent_emails;
	}


	/**
	 * Build Swift_Message instance with all newsletter email data.
	 * @return Swift_Message
	 */
	private function buildMessage()
	{
		// Setup message
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

		return $message;
	}


}