<?php

class ImperialCollegeUserProvider implements Illuminate\Auth\UserProviderInterface {

	protected $model;

	public function __construct($model)
	{
		$this->model = $model;
	}

	public function retrieveByID($identifier)
	{
		return $this->createModel()->synchronizeUser($identifier);
	}

	public function retrieveByCredentials(array $credentials)
	{
		if (isset($credentials['username'])) {
			$this->createModel()->synchronizeUser($credentials['username']);
		}

		$query = $this->createModel()->newQuery();

		foreach ($credentials as $key => $value) {
			if ( ! str_contains($key, 'password')) $query->where($key, $value);
		}

		return $query->first();
	}

	public function validateCredentials(Illuminate\Auth\UserInterface $user, array $credentials)
	{
		return $user->checkPassword($credentials['password']);
	}

	public function createModel()
	{
		$class = '\\'.ltrim($this->model, '\\');

		return new $class;
	}

}