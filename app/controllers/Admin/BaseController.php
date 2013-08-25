<?php
namespace Admin;

class BaseController extends \BaseController {

	public function __construct()
	{
		parent::__construct();

		$this->beforeFilter('csrf', array('on' => array('post', 'put', 'delete')));
	}

}