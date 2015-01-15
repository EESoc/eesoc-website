<?php

use Robbo\Presenter\Presenter;

class SponsorPresenter extends Presenter {

    public function presentLogoPath()
    {
        if ( ! empty($this->logo)) {
            return asset('assets/images/sponsors/' . $this->logo);
        }
    }

    public function presentLogoImage()
    {
        if ( ! empty($this->logo)) {
            return '<img src="' . $this->logo_path . '" alt="' . htmlspecialchars($this->name) . '" class="img-responsive">';
        }
    }

}
