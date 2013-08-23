@extends('layouts.admin')

@section('content')
  <div class="row">
    <div class="col-lg-12">
      <div class="page-header">
        <div class="pull-right btn-group">
          <button class="btn btn-info btn-sm dropdown-toggle" type="button" data-toggle="dropdown">
            <span class="glyphicon glyphicon-refresh"></span>
            Sync Users
            <span class="caret"></span>
          </button>
          <ul class="dropdown-menu">
            <li><a href="{{ URL::action('Admin\UsersEActivitiesController@getBegin') }}">eActivities</a></li>
            <li><a href="{{ URL::action('Admin\UsersEEPeopleController@getBegin') }}">EEPeople</a></li>
          </ul>
        </div>
        <h1>Users</h1>
      </div>
      <div class="row filters">
        {{ Form::open(array('method' => 'get', 'class' => 'form-inline')) }}
          <div class="col-lg-3 form-group {{ ( !! Input::get('query')) ? 'has-success' : '' }}">
            {{ Form::text('query', Input::get('query'), array('class' => 'form-control', 'placeholder' => 'Search Query')) }}
          </div>
          <div class="col-lg-1">
            <button type="submit" class="btn btn-default">
              <span class="glyphicon glyphicon-search"></span>
            </button>
          </div>
        {{ Form::close() }}
        <div class="col-lg-8">
          <ul class="nav nav-pills pull-right">
            <li
              @if ( ! Input::get('query') && ! in_array(Input::get('filter'), array('admins', 'non-admins', 'members', 'non-members')))
                class="active"
              @endif
            >
              <a href="{{ URL::route('admin.users.index') }}">
                <span class="badge pull-right">{{ $everybody_count }}</span>
                Everybody
              </a>
            </li>
            <li {{ (Input::get('filter') === 'admins') ? 'class="active"' : '' }}>
              <a href="{{ URL::route('admin.users.index', array('filter' => 'admins')) }}">
                <span class="badge pull-right">{{ $admins_count }}</span>
                Admins
              </a>
            </li>
            <li {{ (Input::get('filter') === 'non-admins') ? 'class="active"' : '' }}>
              <a href="{{ URL::route('admin.users.index', array('filter' => 'non-admins')) }}">
                <span class="badge pull-right">{{ $non_admins_count }}</span>
                Non-Admins
              </a>
            </li>
            <li {{ (Input::get('filter') === 'members') ? 'class="active"' : '' }}>
              <a href="{{ URL::route('admin.users.index', array('filter' => 'members')) }}">
                <span class="badge pull-right">{{ $members_count }}</span>
                Members
              </a>
            </li>
            <li {{ (Input::get('filter') === 'non-members') ? 'class="active"' : '' }}>
              <a href="{{ URL::route('admin.users.index', array('filter' => 'non-members')) }}">
                <span class="badge pull-right">{{ $non_members_count }}</span>
                Non-Members
              </a>
            </li>
          </ul>
        </div>
      </div>
      <hr>
      <table class="table table-striped table-hover users-table">
        <thead>
          <tr>
            <th>#</th>
            <th>Person</th>
            <th class="text-center">Last Active</th>
            <th class="text-right">Actions</th>
          </tr>
        </thead>
        @foreach($users as $user)
          <tr>
            <td>{{ $user->id }}</td>            <td>
              <div class="media">
                @if ($user->has_image)
                  <a class="pull-left" href="#">
                    <img class="media-object" src="data:image/png;base64,{{base64_encode($user->image_blob)}}" width="81" height="108" alt="{{{ $user->name }}}">
                    <!--<img src="{{ URL::action('Admin\UsersController@getImage', $user->username) }}" width="37" height="50">-->
                  </a>
                @endif
                <div class="media-body">
                  <h4 class="media-heading">
                    {{{ $user->name }}}
                    <small>{{{ $user->username }}}</small>
                  </h4>
                  @if ($user->studentGroup)
                    <p>{{{ $user->studentGroup->name }}}</p>
                  @endif
                  <p>
                    @if ($user->is_admin)
                      <span class="label label-primary">Admin</span>
                    @elseif ($user->is_member)
                      <span class="label label-success">Member</span>
                    @else
                      <span class="label label-danger">Non-Member</span>
                    @endif
                  </p>
                </div>
              </div>
            </td>
            <td class="text-center">
              @if ($user->first_sign_in_at === null)
                <span class="glyphicon glyphicon-remove text-danger"></span>
              @else
                {{ Carbon::createFromTimestamp(strtotime($user->last_sign_in_at))->diffForHumans() }}
              @endif
              <!--
                @if ($user->first_sign_in_at === null)
                  <span class="glyphicon glyphicon-remove text-danger"></span>
                @else
                  <span class="glyphicon glyphicon-ok text-success"></span>
                @endif
              -->
            </td>
            <td class="text-right">
              @if ($user->id === Auth::user()->id)
                It's me :-)
              @else
                <div class="btn-group btn-group-sm">
                  <a href="mailto:{{{ $user->email }}}" class="btn btn-default">
                    <span class="glyphicon glyphicon-envelope"></span>
                    Email
                  </a>

                  <div class="btn-group btn-group-sm">
                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                      Edit <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu" role="menu">
                      <li>
                        @if ($user->is_admin)
                          <a href="{{ URL::action('Admin\UsersController@putDemote', $user->username) }}" data-method="put">
                            <span class="glyphicon glyphicon-star-empty"></span>
                            Demote from Admin
                          </a>
                        @else
                          <a href="{{ URL::action('Admin\UsersController@putPromote', $user->username) }}" data-method="put">
                            <span class="glyphicon glyphicon-star"></span>
                            Promote to Admin
                          </a>
                        @endif
                      </li>
                    </ul>
                  </div>
                </div>
              @endif
            </td>
          </tr>
        @endforeach
      </table>
      {{ $users->appends($paginator_appends)->links() }}
    </div>
  </div>
@stop