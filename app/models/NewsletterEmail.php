<?php

class NewsletterEmail extends Eloquent {

	protected $fillable = array('subject', 'body', 'newsletter_id', 'student_group_ids');

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

	public function setStudentGroupIdsAttribute($student_group_ids)
	{
		$this->attributes['student_group_ids'] = json_encode((array) $student_group_ids);
	}

	public function getStudentGroupIdsAttribute()
	{
		return isset($this->attributes['student_group_ids']) ? (array) json_decode($this->attributes['student_group_ids']) : array();
	}

}