<?php

use Illuminate\Auth\UserInterface;
use Robbo\Presenter\PresentableInterface;

class User extends Eloquent implements UserInterface, PresentableInterface {

	private $imperialCollegeUser;

	public function studentGroup()
	{
		return $this->belongsTo('StudentGroup');
	}

	public function lockers()
	{
		return $this->hasMany('Locker', 'owner_id');
	}

	public function sales()
	{
		return $this->hasMany('Sale');
	}

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

	public function scopeAlphabetically($query)
	{
		return $this->scopeAlphabeticallyUsing($query, 'username');
	}

	public function scopeAlphabeticallyUsing($query, $column)
	{
		return $query->orderBy($column);
	}

	public function scopeSearching($query, $searching)
	{
		return $query->where(function($query) use ($searching) {
			$query->where('username', 'like', "%{$searching}%")
				->orWhere('email',    'like', "%{$searching}%")
				->orWhere('name',     'like', "%{$searching}%");
		});
	}

	public function scopeAdminsFirst($query)
	{
		return $query->orderBy('is_admin', 'DESC');
	}

	public function scopeInGroup($query, $group)
	{
		if ( ! ($group instanceof StudentGroup)) {
			$group = StudentGroup::findOrFail($group);
		}

		return $query->whereIn('student_group_id', $group->related_group_ids);
	}

	public function scopeHasImage($query)
	{
		return $query->whereNotNull('image_blob');
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

	public static function resetMemberships()
	{
		$user = new static;
		return $user->update(array('is_member' => false));
	}

	public static function statistics()
	{
		return array(
			'everybody_count'   => static::count(),
			'admins_count'      => static::admin()->count(),
			'non_admins_count'  => static::nonAdmin()->count(),
			'members_count'     => static::member()->count(),
			'non_members_count' => static::nonMember()->count(),
		);
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

	public function recordSignIn()
	{
		$this->last_sign_in_at = new DateTime;

		// First time signing in
		if ( ! $this->first_sign_in_at) {
			$this->first_sign_in_at = $this->last_sign_in_at;
		}

		$this->sign_in_count++;

		return $this->save();
	}

	/**
	 * Return has image attribute.
	 * 
	 * @return boolean
	 */
	public function getHasImageAttribute()
	{
		return ( ! empty($this->image_blob));
	}

	/**
	 * Return has email attribute.
	 * 
	 * @return boolean
	 */
	public function getHasEmailAttribute()
	{
		return ( ! empty($this->email));
	}

	/**
	 * Check if filter exists.
	 * @param  string $filter
	 * @return boolean
	 */
	public function canFilterBy($filter)
	{
		return isset($this->filter_to_function_map[$filter]);
	}

	/**
	 * Apply filter.
	 * @param  string $filter Filter to be applied.
	 *                        Checks the filter_to_function_map for validity.
	 * @return mixed
	 */
	public function filterBy($filter)
	{
		if (isset($this->filter_to_function_map[$filter])) {
			return $this->{$filter}();
		} else {
			return $this;
		}
	}

	/**
	 * Return a created presenter.
	 *
	 * @return Robbo\Presenter\Presenter
	 */
	public function getPresenter()
	{
		return new UserPresenter($this);
	}

}