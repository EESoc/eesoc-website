<?php

class NewsletterEmail extends Eloquent {

	public function newsletter()
	{
		return $this->belongsTo('Newsletter');
	}

}