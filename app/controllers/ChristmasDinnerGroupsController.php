<?php

class ChristmasDinnerGroupsController extends BaseController {

	public function __construct()
	{
		parent::__construct();

		$this->beforeFilter(function() {
			if (Auth::user()->christmasDinnerSales()->count() === 0) {
				return Redirect::action('UsersController@getDashboard')
					->with('danger', 'You don\'t have a ticket');
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

		// Already a member in any group
		if ($member) {
			return Redirect::route('dashboard.xmas.groups.show', $member->christmas_dinner_group->id)
				->with('danger', 'You already belong to this group. If you wish to join another group, please leave this one.');
		} else {
			if ($user->christmas_tickets_count > 1) {
				return View::make('christmas_dinner_groups.create');
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
				$group = new ChristmasDinnerGroup;
				$group->owner()->associate($user);
				$group->save();

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
				return Redirect::route('dashboard.xmas.groups.create')
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

}