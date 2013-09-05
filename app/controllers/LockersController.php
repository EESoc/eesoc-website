<?php

class LockersController extends BaseController {

	public function getIndex()
	{
		return View::make('lockers.index')
			->with('locker_sales', Auth::user()->sales()->locker()->get())
			->with('locker_floors', LockerFloor::sorted()->get())
			->with('lockers_owned', Auth::user()->lockers)
			->with('unclaimed_lockers_count', Auth::user()->unclaimed_lockers_count);
	}

	public function getClaim($id)
	{
		$locker = $this->findLockerAndAuthorizeClaim($id);
		if ( ! ($locker instanceof Locker)) {
			return $locker;
		}

		return View::make('lockers.claim')
			->with('locker', $locker);
	}

	public function postClaim($id)
	{
		$locker = $this->findLockerAndAuthorizeClaim($id);
		if ( ! ($locker instanceof Locker)) {
			return $locker;
		}

		if ($locker->performClaimBy(Auth::user())) {
			return Redirect::action('LockersController@getIndex')
				->with('success', sprintf('Successfully claimed locker \'%s\'!', $locker->name));
		} else {
			return Redirect::action('LockersController@getIndex')
				->with('danger', 'Something went wrong');
		}
	}

	public function getRent()
	{
		return View::make('lockers.redirect_to_shop')
			->with('redirect_to', URL::action('LockersController@getIndex'));
	}

	private function findLockerAndAuthorizeClaim($id)
	{
		$locker = Locker::findOrFail($id);

		if ( ! $locker->canBeClaimedBy(Auth::user())) {
			return Redirect::action('LockersController@getIndex')
				->with('danger', 'You cannot claim this locker');
		}

		return $locker;
	}

}