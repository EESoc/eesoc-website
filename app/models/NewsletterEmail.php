<?php

class NewsletterEmail extends Eloquent {

	protected $fillable = array('subject', 'body', 'newsletter_id');

	public function newsletter()
	{
		return $this->belongsTo('Newsletter');
	}

	public function queuedEmails()
	{
		return $this->hasMany('NewsletterEmailQueue');
	}

	public function getSendQueueLengthAttribute()
	{
		return $this->queuedEmails()->count();
	}

}