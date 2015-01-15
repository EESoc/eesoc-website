<?php

class UserSubscription extends Eloquent {

    /*
    Relations
     */

    public function user()
    {
        return $this->belongsTo('User');
    }

    public function newsletter()
    {
        return $this->belongsTo('Newsletter');
    }

}
