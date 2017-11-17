<?php

class Newsletter extends Eloquent {

    public function emails()
    {
        return $this->belongsToMany('NewsletterEmail', 'newsletters_newsletter_emails');
    }

    public function subscribers()
    {
        return $this->belongsToMany('User', 'newsletters_users');
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
        $emails = [];

        $student_group_ids = $this->student_groups->lists('id');

        if ( ! empty($student_group_ids)) {
            $users = User::where('is_member','=',1)->whereIn('student_group_id', $student_group_ids)->get();
            /*
			$users = User::where('is_member','=',1)->where(function ($query) use ($student_group_ids) {
						return $query->whereIn('student_group_id', $student_group_ids)
							  ->orWhere('student_group_id', 'IS', "NULL");
					})->get();
					*/
			foreach ($users as $user) {
                $emails[$user->email] = true;
            }

		}
		
		$ousideUsers = User::where('is_member','=',1)->whereNull('student_group_id')->get();
        foreach ($ousideUsers as $user) {
                $emails[$user->email] = true;
            }

        foreach ($this->subscriptions()->with('user')->get() as $subscription) {
            if ($subscription->user && $subscription->user->email) {
                $emails[$subscription->user->email] = true;
            } else if ($subscription->email) {
                $emails[$subscription->email] = true;
            }
        }

        return count($emails);
    }

    //not needed as same as from dB
    //public function getIsSubscribableAttribute()
    //{
        //return $this->is_subscribable === 1;
    //}

}
