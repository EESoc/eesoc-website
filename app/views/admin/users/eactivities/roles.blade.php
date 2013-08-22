@extends('layouts.admin')

@section('content')
  @include('admin.users.eactivities.header')
  <h2>Now, select your society and role&hellip;</h2>
  <hr>
  <h3>Selected Role:</h3>
  <div class="well">
    <h4>{{{ $current_role }}}</h4>
  </div>
  @if (count($other_roles) > 0)
    @if (count($other_roles) === 1)
      <h3>Other Roles</h3>
    @else
      <h3>Other Roles:</h3>
    @endif
    @foreach ($other_roles as $role_id => $role)
      <div class="well">
        <h4>
          {{{ $role }}}
          {{ Form::open(array('action' => 'Admin\UsersEActivitiesController@putSelectRole', 'method' => 'put')) }}
            {{ Form::hidden('role_id', $role_id) }}
            <button type="submit" class="btn btn-info">Select</button>
          {{ Form::close() }}
        </h4>
      </div>
    @endforeach
  @endif
  {{ Form::open(array('action' => 'Admin\UsersEActivitiesController@postPerform')) }}
    <button type="submit" class="btn btn-primary btn-large btn-block">
      <span class="glyphicon glyphicon-refresh"></span>
      Perform Synchronization
    </button>
  {{ Form::close() }}
@stop