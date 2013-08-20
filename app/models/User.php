<?php

use Illuminate\Auth\UserInterface;

class User extends Eloquent implements UserInterface {

	public static $FILTER_TO_FUNCTION_MAP = array(
		'admins'      => 'admin',
		'non-admins'  => 'nonAdmin',
		'members'     => 'member',
		'non-members' => 'nonMember',
	);

	private $imperialCollegeUser;

	public function scopeAdmin($query)
	{
		return $query->where('is_admin', '=', true);
	}

	public function scopeNonAdmin($query)
	{
		return $query->where('is_admin', '=', false);
	}

	public function scopeMember($query)
	{
		return $query->where('is_member', '=', true);
	}

	public function scopeNonMember($query)
	{
		return $query->where('is_member', '=', false);
	}

	public static function findOrCreateWithLDAP($username)
	{
		$username = strtolower($username);

		$imperialCollegeUser = new ImperialCollegeUser($username);
		if ( ! $imperialCollegeUser->exist()) {
			return null;
		}

		// Find or create new User
		$user = static::where('username', '=', $username)->first();
		if ( ! $user) {
			$user = new static;
		}

		$user->setImperialCollegeUser($imperialCollegeUser);
		$user->synchronizeWithLDAP();

		return $user;
	}

	public function getAuthIdentifier()
	{
		return $this->username;
	}

	public function getAuthPassword()
	{
		return null;
	}

	public function setImperialCollegeUser($imperialCollegeUser)
	{
		$this->imperialCollegeUser = $imperialCollegeUser;
	}

	public function getImperialCollegeUser()
	{
		if ( ! isset($this->imperialCollegeUser)) {
			$this->imperialCollegeUser = new ImperialCollegeUser($this->username);
		}

		return $this->imperialCollegeUser;
	}

	public function checkPassword($password)
	{
		return $this->getImperialCollegeUser()->checkPassword($password);
	}

	public function synchronizeWithLDAP()
	{
		$this->username = $this->getImperialCollegeUser()->username;
		$this->name     = $this->getImperialCollegeUser()->name;
		$this->email    = $this->getImperialCollegeUser()->email;
		$this->extras   = implode("\n", $this->getImperialCollegeUser()->info);
		return $this->save();
	}

	public function isAdmin()
	{
		return ((int) $this->is_admin === 1);
	}

	public function recordSignIn()
	{
		$this->last_sign_in_at = new DateTime;
		return $this->save();
	}

}