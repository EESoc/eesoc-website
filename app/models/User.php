<?php

use Illuminate\Auth\UserInterface;

class User extends Eloquent implements UserInterface {

	private $imperialCollegeUser;

	public static function synchronizeUser($username)
	{
		$username = strtolower($username);

		$imperialUser = new ImperialCollegeUser($username);
		if ( ! $imperialUser->exists()) {
			return null;
		}

		// Find or create new User
		$user = static::where('username', '=', $username)->first();
		if ( ! $user) {
			$user = new static;
		}

		$user->username = $imperialUser->username;
		$user->name     = $imperialUser->name;
		$user->email    = $imperialUser->email;
		$user->extras   = implode("\n", $imperialUser->info);

		$user->save();

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

	public function imperialCollegeUser()
	{
		if ( ! isset($this->imperialCollegeUser)) {
			$this->imperialCollegeUser = new ImperialCollegeUser($this->username);
		}

		return $this->imperialCollegeUser;
	}

	public function checkPassword($password)
	{
		return $this->imperialCollegeUser()->checkPassword($password);
	}

	public function synchronizeWithLDAP()
	{
		$this->username = $this->imperialCollegeUser()->username;
		$this->name     = $this->imperialCollegeUser()->name;
		$this->email    = $this->imperialCollegeUser()->email;
		$this->extras   = implode("\n", $this->imperialCollegeUser()->info);
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

	public function scopeAdmin($query)
	{
		return $query->where('is_admin', '=', true);
	}

	public function scopeNonAdmin($query)
	{
		return $query->where('is_admin', '=', false);
	}

}