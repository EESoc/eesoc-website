<?php

class DinnerSale extends Eloquent {

	/*
	Relations
	 */

	public function user()
	{
		return $this->belongsTo('User');
	}

	public function sale()
	{
		return $this->belongsTo('Sale');
	}

}
