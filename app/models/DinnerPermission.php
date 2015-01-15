<?php

class DinnerPermission {
	private $user;

	public static function user(User $user)
	{
		return new static($user);
	}

	public function __construct(User $user)
	{
		$this->user = $user;
	}

	public function canManageGroups()
	{
		return $this->user->dinnerSales()->count();
	}

	public function canCreateNewGroup()
	{
		return !$this->user->dinner_group_member;
	}

	public function canAddUserToGroup(DinnerGroup $group)
	{
        return !$group->isFull();
	}

	public function canJoinGroup(DinnerGroup $group)
	{
		return $this->canAddUserToGroup($group) && !$this->user->dinner_group_member;
	}

	public function canLeaveGroup(DinnerGroup $group)
	{
        return $this->user->dinner_group_member &&
               $this->user->dinner_group_member->dinner_group->id === $group->id;
	}
}
