<?php

use Robbo\Presenter\PresentableInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class Sponsor extends Eloquent implements PresentableInterface {

    protected $fillable = ['name', 'description', 'logo', 'position'];

    public function setLogoAttribute($file)
    {
        if ($file instanceof UploadedFile) {
            $filename = Str::slug($this->name) . '.' . $file->getClientOriginalExtension();
            $newfile = $file->move(base_path('public/assets/images/sponsors'), $filename);
            $this->attributes['logo'] = $filename;
        }
    }

    public function scopeAlphabetically($query)
    {
        return $query->orderBy('name');
    }

    public function scopeSorted($query)
    {
        return $query->orderBy('position');
    }

    /**
     * Return a created presenter.
     *
     * @return Robbo\Presenter\Presenter
     */
    public function getPresenter()
    {
        return new SponsorPresenter($this);
    }

}
