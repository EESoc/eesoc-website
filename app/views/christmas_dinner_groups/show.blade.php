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
  @if (ChristmasPermission::user(Auth::user())->canAddUserToGroup($group))
    {{ Form::model($group, array('route' => array('dashboard.xmas.groups.update', $group->id), 'method' => 'patch')) }}
      <h3>Add more user</h3>
      <p>Form here...</p>
    {{ Form::close() }}
  @endif
  <div class="well">
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