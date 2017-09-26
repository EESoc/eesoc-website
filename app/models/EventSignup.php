<?php

class EventSignup extends Eloquent {

    /*
    Relations
     */

    public function user()
    {
        return $this->belongsTo('User');
    }

    public function sale()
    {
        return $this->belongsTo('EventDay');
    }

}
