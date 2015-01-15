@extends('layouts.application')

@section('content')
  <div class="page-header">
    <h1>Dinner Seating & Meal Planner</h1>
  </div>
  @if ($user && ($unclaimed = $user->unclaimed_dinner_tickets_count))
      <h4 class="alert alert-info">
      You have <strong>{{ $unclaimed }}</strong> unclaimed tickets.

      @if (DinnerPermission::user(Auth::user())->canCreateNewGroup())
            You may either join one of the below groups, or you may <a href="{{ route('dashboard.dinner.groups.create') }}" class="btn btn-primary">Create A New Group</a>.
      @else
            You may not currently create a new group as you are currently in a group; leave that group first.
      @endif
  @endif
    </h4>
  @foreach ($groups as $group)
    <div class="well well-sm">
      <h4>
        <a href="{{ route('dashboard.dinner.groups.show', $group->id) }}">
          Group #{{ $group->id }}
        </a>
      </h4>
      <ul>
        @foreach ($group->members as $member)
          <li>
            @if ($member->is_owner)
              <strong>
            @endif
            {{ $member->name }}
            @if ($member->is_owner)
              </strong>
            @endif
          </li>
        @endforeach
      </ul>
    </div>
  @endforeach
  <p>If you experience any issues, please <a href='mailto:eesoc.webmaster@imperial.ac.uk'>email us</a></p>
@stop
