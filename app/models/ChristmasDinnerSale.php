<?php

class ChristmasDinnerSale extends Eloquent {

	/*
	Relations
	 */

	public function user()
	{
		return $this->belongsTo('User');
	}

}