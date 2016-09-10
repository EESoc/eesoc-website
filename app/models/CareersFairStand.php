<?php

use Robbo\Presenter\PresentableInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class CareersFairStand extends Eloquent implements PresentableInterface {

    protected $table = 'careers_fair_stands';

    protected $fillable = ['name', 'description', 'logo', 'position', 'interested_groups'];

    public function setLogoAttribute($file)
    {
        if ($file instanceof UploadedFile) {
            $filename = Str::slug($this->name) . '.' . $file->getClientOriginalExtension();
            $newfile = $file->move(base_path('public/assets/images/careersfair'), $filename);
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

    public function setInterestedGroups($first, $second, $third, $graduate, $msc, $phd){

        $input = array("1" => $first, "2" => $second, "3" => $third, "4" => $graduate, "5" =>$msc, "6" => $phd);

        $this->interested_groups = json_encode($input);

    }

    public function getInterestedGroups() {
        $array = json_decode($this->interested_groups, true);

        if ($array === false) {
            return false;
        }

        return $array;
    }


    /**
     * Return a created presenter.
     *
     * @return Robbo\Presenter\Presenter
     */
    public function getPresenter()
    {
        return new CareersFairStandPresenter($this);
    }

}
