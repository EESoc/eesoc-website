<?php

use Robbo\Presenter\Presenter;

class BookPresenter extends Presenter {

	public function presentThumbnailImage()
	{
		if ($this->thumbnail) {
			return '<img src="' . $this->thumbnail . '" class="img-thumbnail">';
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
		if ($this->contact_instructions) {
			$lines = explode("\n", $this->contact_instructions);
			$lines = array_map('e', $lines); // Sanitize string
			$html = '<p>' . implode('</p><p>', $lines) . '</p>';
		} else {
			$html = '<p>Email: ' . $this->user->email . '</p>';
		}

		$regex = '/(\S+@\S+\.[^<\s]+)/';
		$replace = '<a href="mailto:$1" class="btn btn-default"><span class="glyphicon glyphicon-envelope"></span> $1</a>';

		return preg_replace($regex, $replace, $html);
	}

}