<?php

class ImperialCollegeUserProvider implements Illuminate\Auth\UserProviderInterface {

	protected $model;

	public function __construct($model)
	{
		$this->model = $model;
	}

	public function retrieveByID($identifier)
	{
		return $this->synchronizeUser($identifier);
	}

	public function retrieveByCredentials(array $credentials)
	{
		if (isset($credentials['username'])) {
			$this->synchronizeUser($credentials['username']);
		}

		$query = $this->createModel()->newQuery();

		foreach ($credentials as $key => $value) {
			if ( ! str_contains($key, 'password')) $query->where($key, $value);
		}

		return $query->first();
	}

	public function validateCredentials(Illuminate\Auth\UserInterface $user, array $credentials)
	{
		$plainPassword = $credentials['password'];
		return pam_auth($user->username, $plainPassword);
	}

	protected function synchronizeUser($identifier)
	{
		$name = ldap_get_name($identifier);
		// Check identifier's existance
		if (empty($name)) {
			return null;
		}

		// Find or create new User
		$user = $this->createModel()->newQuery()->where('username', '=', $identifier)->first();
		if ( ! $user) {
			$user = $this->createModel();
		}

		$user->username = strtolower($identifier);
		$user->name = $name;
		$user->email = ldap_get_mail($identifier);
		$user->extras = implode("\n", (array) ldap_get_info($identifier));

		$user->save();

		return $user;
	}

	public function createModel()
	{
		$class = '\\'.ltrim($this->model, '\\');

		return new $class;
	}

}