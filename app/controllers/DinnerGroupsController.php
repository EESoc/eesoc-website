<?php

class DinnerGroupsController extends BaseController {

    const MAX_TABLES = 24;

    protected $hasExpired = FALSE; /* (time() >= strtotime("2017-01-13 23:59")); */


    public function __construct()
    {
        parent::__construct();

        $this->hasExpired = time() >= strtotime("2017-01-14 23:59");

        $this->beforeFilter(function() {
            if (!DinnerPermission::user(Auth::user())->canManageGroups()) {
                return Redirect::action('UsersController@getDashboard')
                    ->with('danger', "You don't have a ticket");
            }

            if ($this->hasExpired && !Auth::user()->is_admin) {
                return Redirect::action('UsersController@getDashboard')
                    ->with('danger', 'The period for choosing your seat has ended. Should you require further assistance, please <a href="mailto:eesoc.events@imperial.ac.uk">email us</a>.');
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
        return View::make('dinner_groups.index')
            ->with('user', Auth::user())
            ->with('groups', DinnerGroup::orderBy('id')->get());
    }

    /**
     * Creates a single group, redirecting to a form requesting more information
     * (handled by the store() method) if more than one ticket is owned by the
     * user
     *
     * @return Response
     */
    public function create()
    {
        if (self::MAX_TABLES <= DinnerGroup::count())
        {
            return Redirect::route('dashboard.dinner.groups.index')
                    ->with('danger', 'All available tables have already been created. Please join an existing table.');
        }

        $user = Auth::user();

        // Already a member in any group
        if ($member = $user->dinner_group_member) {
            return Redirect::route('dashboard.dinner.groups.show',
                                   $member->dinner_group->id)
                       ->with('danger', 'You already belong to this group. '
                                       .'If you wish to join another group, '
                                       .'please leave this one.');
        }

        $user->getUnclaimedDinnerTicketsCountAttribute();

        // Show multiple user info form if multiple tickets owned to allocate.
        if ($user->unclaimed_dinner_tickets_count > 1) {
            $limit = DinnerGroup::maxSizeByOwner($user);
            $alloc = min($limit, $user->unclaimed_dinner_tickets_count - 1);

            if ($limit >= $user->unclaimed_dinner_tickets_count)
                $limit = null;

            return View::make('dinner_groups.create')
                ->with('group', null)
                ->with('to_allocate', $alloc)
                ->with('limit', $limit);
        }

        $group = DinnerGroup::createWithOwner($user);

        return Redirect::route('dashboard.dinner.groups.show', $group->id)
                   ->with('success', 'Your group has been created!');
    }

    /**
     * Adds multiple users to a group.
     *
     * @return Response
     */
    public function store()
    {
        $user = Auth::user();

        // Already a member in any group
        if ($member = $user->dinner_group_member) {
            return Redirect::route('dashboard.dinner.groups.show',
                                   $member->dinner_group->id)
                   ->with('danger',
                          'You already belong to a group.'
                         .'If you wish to join another group, '
                         .'please leave this one.');
        }

        $user->getUnclaimedDinnerTicketsCountAttribute();

        if ($user->unclaimed_dinner_tickets_count === 1)
            return Redirect::route('dashboard.dinner.groups.create');

        if ($group_id = Input::get('group_id')) {
            $group = DinnerGroup::findOrFail($group_id);
            $group->addMember($user);
        } else {
            $group = DinnerGroup::createWithOwner($user);
        }

        $rules     = [];
        $sizeLimit = min($group->max_size, $user->unclaimed_dinner_tickets_count);

        for ($i = 1; $i < $sizeLimit; $i++)
            $rules["user_{$i}"] = 'required';

        $validator = Validator::make(Input::all(), $rules);

        if (!$validator->passes()) {
            return Redirect::route('dashboard.dinner.groups.create',
                                   ['group_id' => Input::get('group_id')])
                    ->withInput()
                    ->withErrors($validator);
        }

        for ($i = 1; $i < $sizeLimit; $i++)
            $group->addMember(Input::get("user_$i"));

        return Redirect::route('dashboard.dinner.groups.show', $group->id)
            ->with('success', 'Your group has been created!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $group = DinnerGroup::findOrFail($id);

        return View::make('dinner_groups.show')->with('group', $group);
    }

    /**
     * Add the current user to a group.
     *
     * @param  $id The group to which users should be added.
     * @return Response
     */
    public function update($id)
    {
        $user  = Auth::user();
        $group = DinnerGroup::findOrFail($id);

        if (!DinnerPermission::user($user)->canAddUserToGroup($group)) {
            return Redirect::route('dashboard.dinner.groups.show', $group->id)
                ->with('danger', 'You cannot add any more users to this group');
        }

        if (!DinnerPermission::user($user)->canJoinGroup($group)) {
            return Redirect::route('dashboard.dinner.groups.show', $group->id)
                ->with('danger', 'You cannot join this group');
        }

        $group->addMember($user);

        return Redirect::route('dashboard.dinner.groups.show', $group->id)
            ->with('success', 'You have joined this group');
    }

    /**
     * Remove the current user from the group.
     *
     * @param  int  $id The group id.
     * @return Response
     */
    public function destroy($id)
    {
        $user  = Auth::user();
        $group = DinnerGroup::findOrFail($id);

        if (!DinnerPermission::user($user)->canLeaveGroup($group)) {
            return Redirect::route('dashboard.dinner.groups.show', $group->id)
                ->with('danger', 'You cannot leave this group');
        }

        $removed = $group->removeMember($user);

        if ($removed === true) {
            return Redirect::route('dashboard.dinner.groups.show', $group->id)
                ->with('success', 'You have left this group');
        }else{
            return $removed;
        }
    }

    public function removeMember()
    {
        $user     = Auth::user();
        $member   = DinnerGroupMember::findOrFail((integer) Input::get('remove'));
        $oldGroup = $member->dinner_group_id;

        if ($user->id !== $member->ticket_purchaser_id)
        {
            return Redirect::route('dashboard.dinner.groups.show', $oldGroup)
                ->with('danger', 'You cannot remove that guest from their table.');
        }

        if ($member->is_owner && !DinnerGroup::CAN_LEAVE_OWN_GRP)
        {
            return Redirect::route('dashboard.dinner.groups.show', $oldGroup)
                ->with('danger', 'You cannot leave from your own group.');
        }


        $group = $member->dinnerGroup()->first();
        //$membersInGroup = $group->members()->get()->count();
        $membersInGroup = DinnerGroupMember::where('dinner_group_id','=',$oldGroup)->count();

        $member->delete();

        //If user was the last person to leave the group, delete the group as well.
        if ($membersInGroup <= 1){
            $group->delete();
            return Redirect::route('dashboard.dinner.groups.index')
                ->with('success', 'You have left the group.');
        }
		
		$group->is_full = $membersInGroup - 1 >= $group->max_size;
        
		$group->save();		

        return Redirect::route('dashboard.dinner.groups.show', $oldGroup)
            ->with('success', 'Your guest was removed.');
    }

    public function addMember()
    {
        $user     = Auth::user();
        $group    = DinnerGroup::findOrFail((integer) Input::get('group'));
        $oldGroup = $group->id;

        $user->getUnclaimedDinnerTicketsCountAttribute();

        if (!DinnerPermission::user($user)->canAddUserToGroup($group) ||
            (!$user->DinnerGroupMember && $user->unclaimed_dinner_tickets_count <= 1) ||
            ($user->DinnerGroupMember && $user->unclaimed_dinner_tickets_count < 1)) {
            return Redirect::route('dashboard.dinner.groups.show', $group->id)
                ->with('danger', 'You cannot add any more guests to this group.');
        }

        $group->addMember(Input::get("new_guest"));

        return Redirect::route('dashboard.dinner.groups.show', $oldGroup)
            ->with('success', 'Your guest was added.');
    }

    public function updateMenuChoice()
    {
        $member = DinnerGroupMember::findOrFail((integer) Input::get('member'));
        $courses = [
            "starter",
            "main",
            "dessert"
        ];

        foreach($courses as $course){
            $vName = "choice_$course";
            $choice = Input::get($vName);

            echo $vName." -> ".$choice;
            //Choice validation
            switch ($course)
            {
            case 'main':
                if ($choice <= 4 && $choice >= 1){ break; }

            case 'starter':
            case 'dessert':
                if ($choice <= 3 && $choice >= 1){ break; }

            default:
                return Redirect::route('dashboard.dinner.groups.show', $member->dinner_group->id)
                    ->with('danger', 'Please select the menu choices fo all courses for '.$member->name.'.');
                //throw new \InvalidArgumentException("An invalid choice was specified.");

            }

            $member->$vName = $choice;
        }


        $member->special_req = Input::get('special_req');

        $member->save();

        return Redirect::route('dashboard.dinner.groups.show', $member->dinner_group->id)
            ->with('success', 'Thanks! The menu choice updated for '.$member->name.'.');
    }
}
