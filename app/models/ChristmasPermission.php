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
		return $group->members->count() < 10;
	}

	public function canJoinGroup(ChristmasDinnerGroup $group)
	{
		return $this->canAddUserToGroup($group) && ! $this->user->christmas_dinner_group_member;
	}

	public function canLeaveGroup(ChristmasDinnerGroup $group)
	{
		return $this->user->christmas_dinner_group_member && $this->user->christmas_dinner_group_member->christmas_dinner_group->id === $group->id;
	}
}