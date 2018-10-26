<?php

use Robbo\Presenter\PresentableInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class CommitteeMember extends Eloquent implements PresentableInterface {

    protected $fillable = ['name', 'role', 'short_description', 'description', 'logo', 'list_position', 'githubURL', 'facebookURL', 'email'];

	// public function events()
    // {
    //     return $this->belongsToMany('EventDay', 'event_sponsor', 'sponsor_id', 'event_id')->withTimestamps();
    // }
	
    public function setLogoAttribute($file)
    {
        if ($file instanceof UploadedFile) {
            $filename = Str::slug($this->name) . '.' . $file->getClientOriginalExtension();
            $newfile = $file->move(base_path('public/files/committee/prof-pics'), $filename);
            $this->attributes['logo'] = $filename;
        }
    }

    public function scopeAlphabetically($query)
    {
        return $query->orderBy('name');
    }

    public function scopeSorted($query)
    {
        return $query->orderBy('list_position');
    }
	

    /**
     * Return a created presenter.
     *
     * @return Robbo\Presenter\Presenter
     */
    public function getPresenter()
    {
        return new CommitteeMemberPresenter($this);
    }

}
