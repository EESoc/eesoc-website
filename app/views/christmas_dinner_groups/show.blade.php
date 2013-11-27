@extends('layouts.application')

@section('content')
  <div class="page-header">
    <h1>Christmas Dinner Seating Planner</h1>
  </div>
  <p>
    <a href="{{ route('dashboard.xmas.groups.index') }}" class="btn btn-default">Go Back</a>
  </p>
  @if ($group->owner_id == Auth::user()->id)
  <p class="alert alert-info">
    This is your group!
  </p>
  @endif
  <hr>
  @if (ChristmasPermission::user(Auth::user())->canJoinGroup($group))
    {{ Form::model($group, array('route' => array('dashboard.xmas.groups.update', $group->id), 'method' => 'patch')) }}
      {{ Form::hidden('user_id', Auth::user()->id) }}
      {{ Form::submit('Join this Group', ['class' => 'btn btn-primary']) }}
    {{ Form::close() }}
    <hr>
  @endif
  @if (ChristmasPermission::user(Auth::user())->canLeaveGroup($group))
    {{ Form::model($group, array('route' => array('dashboard.xmas.groups.destroy', $group->id), 'method' => 'delete')) }}
      {{ Form::hidden('user_id', Auth::user()->id) }}
      {{ Form::submit('Leave this Group', ['class' => 'btn btn-danger']) }}
    {{ Form::close() }}
    <hr>
  @endif
  @if (ChristmasPermission::user(Auth::user())->canAddUserToGroup($group))
    {{ Form::model($group, array('route' => array('dashboard.xmas.groups.update', $group->id), 'method' => 'patch')) }}
      <h4>Add more users!</h4>
      <div class="row">
        <div class="col-lg-4">
          <div class="input-group">
            {{ Form::select('user_id', User::hasUnclaimedChristmasTickets()->get()->lists('name', 'id'), null, ['class' => 'form-control']) }}
            <div class="input-group-btn">
              {{ Form::submit('Add', ['class' => 'btn btn-info']) }}
            </div>
          </div>
        </div>
      </div>
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