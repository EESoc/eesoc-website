@extends('layouts.application')

@section('content')
  <div class="page-header">
    <h1>EESoc Trip Room Selection</h1>

  </div>
  @if ($user && $user->getUnclaimedDinnerTicketsCountAttribute() && ($unclaimed = $user->unclaimed_dinner_tickets_count))
      <h4 class="alert alert-info">
      You have <strong>{{ $unclaimed }}</strong> unclaimed tickets.

      @if (DinnerPermission::user(Auth::user())->canCreateNewGroup())
        @if (count($groups) >= DinnerGroup::MAX_NO_OF_GROUPS)
          All rooms have <b>already been created</b>. Please join an existing room.
        @else   
            You may either join one of the below groups, or you may <a href="{{ route('dashboard.dinner.groups.create') }}" class="btn btn-primary">Create A New Group</a>.
            <br/><b>Please do not create a group if you want to join another group, as you will not be able to leave your own group.</b>
        @endif
      @else
            You may not create a new group as you are currently in a group.
      @endif
  @endif
    </h4>
  @foreach ($groups as $index => $group)
    @if ($group->is_full)
		<div class="well well-sm" style="color: grey;">
	@else	
		<div class="well well-sm ">
	@endif
      <h4>
        <a href="{{ route('dashboard.dinner.groups.show', $group->id) }}">
          Room #{{ $index+1 }}
        </a>
		@if ($group->is_full)
			- <span style="color:red;">Full</span>
		@elseif ($group->emptyCount() == 1)
      - <span style="color:green;">1 space left!</span>
    @else
      - <span style="color:green;">{{ $group->emptyCount() }} spaces left</span>
    @endif
    		
      </h4>
      <ul>
        @foreach ($group->members as $member)
          <li>
            @if ($member->is_owner)
              <strong>
            @endif
            {{ $member->name }}
            @if ($member->user_id && ($user = User::find($member->user_id)))
                <small>({{ $user->username}})</small>
            @endif
            @if ($member->is_owner)
              </strong>
            @endif
          </li>
        @endforeach
      </ul>
    </div>
  @endforeach
  <p>If you experience any issues, please <a href='mailto:eesocweb@ic.ac.uk?Subject=Dinner%20Issue'>email the webmaster</a>.</p>
@stop
