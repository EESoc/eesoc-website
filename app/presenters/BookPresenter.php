<?php

use Robbo\Presenter\Presenter;

class BookPresenter extends Presenter {

	public function presentThumbnailImage()
	{
		if ($this->thumbnail) {
			return '<img src="' . $this->thumbnail . '" class="img-responsive">';
		}
	}

	public function presentPrice()
	{
		return '&pound;' . number_format($this->price_in_pence / 100, 2);
	}

	public function presentRawPrice()
	{
		return sprintf('%.2f', $this->price_in_pence / 100);
	}

	public function presentContactInstructionParagraphs()
	{
		$lines = explode("\n", $this->contact_instructions);
		$lines = array_map('e', $lines);
		return '<p>' . implode('</p><p>', $lines) . '</p>';
	}

}