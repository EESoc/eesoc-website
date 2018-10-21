<?php

use Robbo\Presenter\Presenter;

class CommitteeMemberPresenter extends Presenter {

    public function presentLogoPath()
    {
        if ( ! empty($this->logo)) {
            return asset('files/committee/prof-pics/' . $this->logo);
        }
    }

    public function presentLogoImage()
    {
        if ( ! empty($this->logo)) {
            return '<img src="' . $this->logo_path . '" alt="' . htmlspecialchars($this->name) . '" class="img-responsive">';
        }
    }

}
