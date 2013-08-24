<?php
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ImperialCollegeUser {

	protected $username;

	protected $attributes = array();

	public function __construct($username)
	{
		$this->username = strtolower($username);
	}

	public static function find($username)
	{
		return new static($username);
	}

	public static function findOrFail($username)
	{
		$instance = new static($username);

		if ( ! $instance->exist()) {
			throw new ModelNotFoundException;
		}

		return $instance;
	}

	public function exist()
	{
		return ( !! ldap_get_name($this->username));
	}

	public function checkPassword($password)
	{
		// @TODO Log when not in HTTPS mode
		return pam_auth($this->username, $password);
	}

	public function getUsername()
	{
		return $this->username;
	}

	public function getName()
	{
		return ldap_get_name($this->username);
	}

	public function getNames()
	{
		return (array) ldap_get_names($this->username);
	}

	public function getFirstName()
	{
		$names = $this->names();
		return (isset($names[0])) ? $names[0] : null;
	}

	public function getLastName()
	{
		$names = $this->names();
		return (isset($names[1])) ? $names[1] : null;
	}

	public function getEmail()
	{
		return ldap_get_mail($this->username);
	}

	public function getInfo()
	{
		return (array) ldap_get_info($this->username);
	}

	public function __get($key)
	{
		if (method_exists($this, 'get'.studly_case($key))) {
			return $this->{'get'.studly_case($key)}($key);
		}
	}

}