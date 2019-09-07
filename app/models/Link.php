<?php

//use Robbo\Presenter\PresentableInterface;
//use Symfony\Component\HttpFoundation\File\UploadedFile;

class Link extends Eloquent {

    protected $fillable = ['slug', 'full_url', 'expiry_date'];

	// public function events()
    // {
    //     return $this->belongsToMany('EventDay', 'event_sponsor', 'sponsor_id', 'event_id')->withTimestamps();
    // }
    
    //return non-expired links
    public function scopeActive($query)
    {
        return $query->where('expiry_date', '>=', date('Y-m-d'))
                     ->orWhereNull('expiry_date');
    }


    // /**
    //  * Return a created presenter.
    //  *
    //  * @return Robbo\Presenter\Presenter
    //  */
    // public function getPresenter()
    // {
    //     return new LinkPresenter($this);
    // }

}
