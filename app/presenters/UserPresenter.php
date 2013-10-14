<?php

use Robbo\Presenter\Presenter;

class UserPresenter extends Presenter {

	public function presentRoleLabel()
	{
		if ($this->is_admin) {
			return '<span class="label label-primary">Admin</span>';
		} else if ($this->is_member) {
			return '<span class="label label-success">Member</span>';
		} else {
			return '<span class="label label-danger">Non-Member</span>';
		}
	}

	public function presentLastActive()
	{
		if ($this->first_sign_in_at === null) {
			return '<span class="glyphicon glyphicon-remove text-danger"></span>';
		} else {
			return Carbon::createFromTimestamp(strtotime($this->last_sign_in_at))->diffForHumans();
		}
	}

	public function presentEmailUrl()
	{
		if ( ! empty($this->email)) {
			return "mailto:{$this->email}";
		}
	}

	public function presentImageUrl()
	{
		return URL::action('Admin\UsersController@getImage', $this->username);
	}

	public function presentPromotionUrl()
	{
		return URL::action('Admin\UsersController@putPromote', $this->username);
	}

	public function presentDemotionUrl()
	{
		return URL::action('Admin\UsersController@putDemote', $this->username);
	}

	public function presentPretendUrl()
	{
		return URL::action('Admin\UsersController@getPretend', $this->username);
	}

}