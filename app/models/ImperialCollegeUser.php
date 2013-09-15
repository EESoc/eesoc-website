<?php
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ImperialCollegeUser {

	protected $username;

	protected $attributes = array();

	/**
	 * @param string $username
	 */
	public function __construct($username)
	{
		$this->username = strtolower($username);
	}

	/**
	 * Factory
	 * 
	 * @param  string $username
	 * @return ImperialCollegeUser
	 */
	public static function find($username)
	{
		return new static($username);
	}

	/**
	 * Factory
	 * 
	 * @param  string $username
	 * @return ImperialCollegeUser
	 */
	public static function findOrFail($username)
	{
		$instance = new static($username);

		if ( ! $instance->exist()) {
			throw new ModelNotFoundException;
		}

		return $instance;
	}

	/**
	 * Determine existance of user.
	 * 
	 * @return boolean
	 */
	public function exist()
	{
		return ( !! ldap_get_name($this->username));
	}

	/**
	 * Verify user's password
	 * 
	 * @param  string $password
	 * @return boolean
	 */
	public function checkPassword($password)
	{
		return pam_auth($this->username, $password);
	}

	/**
	 * Username
	 * 
	 * @return string
	 */
	public function getUsername()
	{
		return $this->username;
	}

	/**
	 * Name
	 * 
	 * @return string
	 */
	public function getName()
	{
		return ldap_get_name($this->username);
	}

	/**
	 * Names (First Name, Last Name)
	 * 
	 * @return array
	 */
	public function getNames()
	{
		return (array) ldap_get_names($this->username);
	}

	/**
	 * First name
	 * 
	 * @return string
	 */
	public function getFirstName()
	{
		$names = $this->names();
		return (isset($names[0])) ? $names[0] : null;
	}

	/**
	 * Last name
	 * 
	 * @return string
	 */
	public function getLastName()
	{
		$names = $this->names();
		return (isset($names[1])) ? $names[1] : null;
	}

	/**
	 * Email
	 * 
	 * @return string
	 */
	public function getEmail()
	{
		return ldap_get_mail($this->username);
	}

	/**
	 * User information
	 * 
	 * @return array
	 */
	public function getInfo()
	{
		return (array) ldap_get_info($this->username);
	}

	/**
	 * Magic get function
	 * @param  string $key
	 * @return mixed
	 */
	public function __get($key)
	{
		if (method_exists($this, 'get'.studly_case($key))) {
			return $this->{'get'.studly_case($key)}($key);
		}

		return parent::__get($key);
	}

}