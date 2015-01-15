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
        $emails = [];

        $student_group_ids = $this->student_groups->lists('id');

        if ( ! empty($student_group_ids)) {
            $users = User::whereIn('student_group_id', $student_group_ids)->get();
            foreach ($users as $user) {
                $emails[$user->email] = true;
            }
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

}
