@extends('layouts.application')

@section('content')
  <div class="page-header">
    <h1>Dinner Seating &amp; Menu Planner</h1>
  </div>
  <div>
    <h2>Group #{{ $group->id }}</h2>
      @if ($group->owner_id == Auth::user()->id)
      <br>
              <span class="alert alert-info">
                This group belongs to you.
              </span>
        <br><br>
      @endif
      <p>Members:</p>
    <ul>
      @if (!$group->members->count())
          <li><em>(Empty Group)</em></li>
      @endif
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
    <hr>
  </div>
<p>
  @if (DinnerPermission::user(Auth::user())->canJoinGroup($group))
    {{ Form::model($group, array('route' => array('dashboard.dinner.groups.update', $group->id), 'method' => 'patch')) }}
      {{ Form::hidden('user_id', Auth::user()->id) }}
      {{ Form::submit('Join this Group', ['class' => 'btn btn-primary']) }}
    {{ Form::close() }}
    <br>
  @endif
  @if (DinnerPermission::user(Auth::user())->canLeaveGroup($group))
    {{ Form::model($group, array('route' => array('dashboard.dinner.groups.destroy', $group->id), 'method' => 'delete')) }}
      {{ Form::hidden('user_id', Auth::user()->id) }}
      {{ Form::submit('Leave this Group', ['class' => 'btn btn-danger']) }}
    {{ Form::close() }}
    <br>
  @endif
    <a href="{{ route('dashboard.dinner.groups.index') }}" class="btn btn-default">Go Back</a>
</p>
@stop
