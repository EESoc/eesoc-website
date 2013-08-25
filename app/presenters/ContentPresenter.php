<?php

class ContentPresenter extends Robbo\Presenter\Presenter {

	public function presentTemplateCode()
	{
		return "@content('{$this->slug}')";
	}

}