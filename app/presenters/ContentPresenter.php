<?php

use Robbo\Presenter\Presenter;

class ContentPresenter extends Presenter {

	public function presentTemplateCode()
	{
		return "@content('{$this->slug}')";
	}

}