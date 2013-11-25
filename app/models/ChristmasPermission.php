<?php

class ChristmasPermission {
	private $user;

	public static function user(User $user)
	{
		return new static($user);
	}

	public function __construct(User $user)
	{
		$this->user = $user;
	}

	public function canCreateNewGroup()
	{
		return ( ! $this->user->christmas_dinner_group_member);
	}

	public function canAddUserToGroup(ChristmasDinnerGroup $group)
	{
		return $group->users->count() < 10;
	}
}