<?php

class ChristmasDinnerGroupsController extends BaseController {

	public function __construct()
	{
		parent::__construct();

		$this->beforeFilter(function() {
			if ( ! ChristmasPermission::user(Auth::user())->canManageGroups()) {
				return Redirect::action('UsersController@getDashboard')
					->with('danger', 'You don\'t have a ticket');
			}

			if ( ! Auth::user()->is_admin) {
				return Redirect::action('UsersController@getDashboard')
					->with('danger', 'The period for choosing your seat has ended. Should you require further assistance, please <a href="mailto:christos.karpis11@imperial.ac.uk">email us</a>.');
			}
		});
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		return View::make('christmas_dinner_groups.index')
			->with('groups', ChristmasDinnerGroup::all());
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$user = Auth::user();
		$member = $user->christmas_dinner_group_member;

		if ($group_id = Input::get('group_id')) {
			$group = ChristmasDinnerGroup::findOrFail($group_id);
		} else {
			$group = null;
		}

		// Already a member in any group
		if ($member) {
			return Redirect::route('dashboard.xmas.groups.show', $member->christmas_dinner_group->id)
				->with('danger', 'You already belong to this group. If you wish to join another group, please leave this one.');
		} else {
			if ($user->christmas_tickets_count > 1) {
				return View::make('christmas_dinner_groups.create')
					->with('group', $group);
			} else {
				$group = new ChristmasDinnerGroup;
				$group->owner()->associate($user);
				$group->save();
				return Redirect::route('dashboard.xmas.groups.show', $group->id)
					->with('success', 'Your group has been created!');
			}
		}
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$user = Auth::user();
		$member = $user->christmas_dinner_group_member;

		// Already a member in any group
		if ($member) {
			return Redirect::route('dashboard.xmas.groups.show', $member->christmas_dinner_group->id)
				->with('danger', 'You already belong to this group. If you wish to join another group, please leave this one.');
		} else {
			if ($user->christmas_tickets_count === 1) {
				return Redirect::route('dashboard.xmas.groups.create');
			}

			$rules = [];
			for ($i = 1; $i < $user->christmas_tickets_count; $i++) {
				$rules["user_{$i}"] = 'required';
			}

			$validator = Validator::make(Input::all(), $rules);

			if ($validator->passes()) {
				if ($group_id = Input::get('group_id')) {
					$group = ChristmasDinnerGroup::findOrFail($group_id);

					$member = new ChristmasDinnerGroupMember;
					$member->christmasDinnerGroup()->associate($group);
					$member->user()->associate($user);
					$member->addedByUser()->associate($user);
					$member->ticketPurchaser()->associate($user);
					$member->save();
				} else {
					$group = new ChristmasDinnerGroup;
					$group->owner()->associate($user);
					$group->save();
				}

				for ($i = 1; $i < $user->christmas_tickets_count; $i++) {
					$member = new ChristmasDinnerGroupMember;
					$member->christmasDinnerGroup()->associate($group);
					$member->name = Input::get("user_{$i}");
					$member->addedByUser()->associate($user);
					$member->ticketPurchaser()->associate($user);
					$member->save();
				}

				return Redirect::route('dashboard.xmas.groups.show', $group->id)
					->with('success', 'Your group has been created!');
			} else {
				return Redirect::route('dashboard.xmas.groups.create', ['group_id' => Input::get('group_id')])
					->withInput()
					->withErrors($validator);
			}
		}
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$group = ChristmasDinnerGroup::findOrFail($id);

		return View::make('christmas_dinner_groups.show')
			->with('group', $group);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$user = Auth::user();
		// $target_user = User::findOrFail(Input::get('user_id'));
		$group = ChristmasDinnerGroup::findOrFail($id);

		// if ( ! ChristmasPermission::user($user)->canAddUserToGroup($group)) {
		// 	return Redirect::route('dashboard.xmas.groups.show', $group->id)
		// 		->with('danger', 'You cannot add any more users to this group');
		// }

		if ( ! ChristmasPermission::user($user)->canJoinGroup($group)) {
			return Redirect::route('dashboard.xmas.groups.show', $group->id)
				->with('danger', 'You cannot join this group');
		}

		if ($user->christmas_tickets_count > 1) {
			return Redirect::route('dashboard.xmas.groups.create', ['group_id' => $group->id]);
		}

		$member = new ChristmasDinnerGroupMember;
		$member->christmasDinnerGroup()->associate($group);
		$member->user()->associate($user);
		$member->addedByUser()->associate($user);
		$member->ticketPurchaser()->associate($user);
		$member->save();

		return Redirect::route('dashboard.xmas.groups.show', $group->id)
			->with('success', 'You have joined this group');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$user = Auth::user();
		$group = ChristmasDinnerGroup::findOrFail($id);

		if ( ! ChristmasPermission::user($user)->canLeaveGroup($group)) {
			return Redirect::route('dashboard.xmas.groups.show', $group->id)
				->with('danger', 'You cannot leave this group');
		}

		$group->members()
			->where('user_id', '=', $user->id)
			->delete();

		return Redirect::route('dashboard.xmas.groups.show', $group->id)
			->with('success', 'You have left this group');
	}

}