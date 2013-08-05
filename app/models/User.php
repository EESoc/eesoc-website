<?php

use Illuminate\Auth\UserInterface;

class User extends Eloquent implements UserInterface {

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
		return new ImperialCollegeUser($this->username);
	}

	public function checkPassword($password)
	{
		return $this->imperialCollegeUser()->checkPassword($password);
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