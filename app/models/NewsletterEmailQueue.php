<?php

class NewsletterEmailQueue extends Eloquent {

	protected $table = 'newsletter_email_queue';

	public $timestamps = false;

	public function email()
	{
		return $this->belongsTo('NewsletterEmail', 'newsletter_email_id');
	}

}