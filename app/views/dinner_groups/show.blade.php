@extends('layouts.application')

@section('content')
  <div class="page-header">
    <h1>Dinner Seating Planner</h1>
  </div>
  <p>
    <a href="{{ route('dashboard.dinner.groups.index') }}" class="btn btn-default">Go Back</a>
  </p>
  @if ($group->owner_id == Auth::user()->id)
  <p class="alert alert-info">
    This is your group!
  </p>
  @endif
  <hr>
  @if (DinnerPermission::user(Auth::user())->canJoinGroup($group))
    {{ Form::model($group, array('route' => array('dashboard.dinner.groups.update', $group->id), 'method' => 'patch')) }}
      {{ Form::hidden('user_id', Auth::user()->id) }}
      {{ Form::submit('Join this Group', ['class' => 'btn btn-primary']) }}
    {{ Form::close() }}
    <hr>
  @endif
  @if (DinnerPermission::user(Auth::user())->canLeaveGroup($group))
    {{ Form::model($group, array('route' => array('dashboard.dinner.groups.destroy', $group->id), 'method' => 'delete')) }}
      {{ Form::hidden('user_id', Auth::user()->id) }}
      {{ Form::submit('Leave this Group', ['class' => 'btn btn-danger']) }}
    {{ Form::close() }}
    <hr>
  @endif
  <div>
    <h2>Group #{{ $group->id }}</h2>
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
@stop
