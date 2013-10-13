<?php

class Newsletter extends Eloquent {

	public function emails()
	{
		return $this->belongsToMany('NewsletterEmail', 'newsletters_newsletter_emails');
	}

	public function subscribers()
	{
		return $this->belongsToMany('User', 'user_subscriptions');
	}

	public function subscriptions()
	{
		return $this->hasMany('UserSubscription');
	}

	public function studentGroups()
	{
		return $this->belongsToMany('StudentGroup', 'newsletters_student_groups');
	}

	public function getRecipientsCountAttribute()
	{
		$count = 0;

		$student_group_ids = $this->student_groups->lists('id');

		if ( ! empty($student_group_ids)) {
			$count += User::whereIn('student_group_id', $student_group_ids)->count();
		}

		$query = $this->subscribers();

		if ( ! empty($student_group_ids)) {
			$query->whereNotIn('user_id', function($query) use ($student_group_ids) {
				return $query
					->select('id')
					->from('users')
					->whereIn('student_group_id', $student_group_ids);
			});
		}

		$count += $query->count();

		return $count;
	}

}