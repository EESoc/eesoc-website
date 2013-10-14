<?php

class NewsletterEmailQueue extends Eloquent {

	protected $table = 'newsletter_email_queue';

	public $timestamps = false;

	public function email()
	{
		return $this->belongsTo('NewsletterEmail', 'newsletter_email_id');
	}

	public function scopePendingSend($query)
	{
		return $query->where('sent', '=', '0');
	}

	public function scopeSent($query)
	{
		return $query->where('sent', '=', '1');
	}

	public function getTrackingPixelUrlAttribute()
	{
		return url('emails/track/' . $this->tracker_token . '.gif');
	}

}