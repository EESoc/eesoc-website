@extends('layouts.admin')

@section('content')
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
  <div class="row">
    <div class="col-lg-3">
      <ul class="nav nav-pills nav-stacked">
        <li
          @if (
            ! in_array(Input::get('filter'), array('admins', 'non-admins', 'members', 'non-members')) &&
            ! Input::get('group_id')
          )
            class="active"
          @endif
        >
          <a href="{{ URL::route('admin.users.index') }}">
            <span class="badge pull-right">{{ $everybody_count }}</span>
            Everybody
          </a>
        </li>
        <li class="
          dropdown
          @if (Input::get('group_id'))
            active
          @endif
        ">
          <a class="dropdown-toggle" data-toggle="dropdown" href="#">
            @if ($selected_group)
              {{$selected_group->name}}
            @else
              Year Groups
            @endif
            <span class="caret"></span>
          </a>
          <ul class="dropdown-menu">
            @include('admin.users.groups_list', ['groups' => $groups, 'level' => 0])
          </ul>
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
    <div class="col-lg-9">
      {{ Form::open(array('method' => 'get', 'class' => 'form-inline')) }}
        @foreach ($request_params as $key => $value)
          @if ( ! empty($value))
            {{ Form::hidden($key, $value) }}
          @endif
        @endforeach
        <div class="form-group {{ ( !! Input::get('query')) ? 'has-success' : '' }}">
          <div class="input-group">
            {{ Form::text('query', Input::get('query'), array('class' => 'form-control', 'placeholder' => 'Search Query')) }}
            <span class="input-group-btn">
              <button type="submit" class="btn btn-default">
                <span class="glyphicon glyphicon-search"></span>
              </button>
            </span>
          </div>
        </div>
      {{ Form::close() }}
      <hr>
      <table
        class="table table-striped table-hover users-table"
        @if ( ! empty($request_params['query']))
          data-highlight="{{{ $request_params['query'] }}}"
        @endif
      >
        <thead>
          <tr>
            <th>#</th>
            <th>Person</th>
            <th class="text-center">Last Active</th>
            <th class="text-right">Actions</th>
          </tr>
        </thead>
        @foreach ($users as $user)
          <tr>
            <td>{{ $user->id }}</td>
            <td>
              <div class="media">
                @if ($user->has_image)
                  <a class="pull-left" href="{{ URL::action('Admin\UsersController@getImage', $user->username) }}">
                    <img class="media-object" src="{{ URL::action('Admin\UsersController@getImage', $user->username) }}" width="81" height="108" alt="{{{ $user->name }}}">
                  </a>
                @endif
                <div class="media-body">
                  <h4 class="media-heading">
                    {{{ $user->name }}}
                    <small>{{{ $user->username }}}</small>
                  </h4>
                  <p>
                    @if ($user->is_admin)
                      <span class="label label-primary">Admin</span>
                    @elseif ($user->is_member)
                      <span class="label label-success">Member</span>
                    @else
                      <span class="label label-danger">Non-Member</span>
                    @endif
                  </p>
                  @if ($user->studentGroup)
                    <p>{{{ $user->studentGroup->name }}}</p>
                  @endif
                  @if ($user->cid)
                    <p><small>CID: {{{ $user->cid }}}</small></p>
                  @endif
                </div>
              </div>
            </td>
            <td class="text-center">
              @if ($user->first_sign_in_at === null)
                <span class="glyphicon glyphicon-remove text-danger"></span>
              @else
                {{ Carbon::createFromTimestamp(strtotime($user->last_sign_in_at))->diffForHumans() }}
              @endif
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
                      <span class="glyphicon glyphicon-wrench"></span>
                      Edit
                      <span class="caret"></span>
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
      {{ $users->appends($request_params)->links() }}
    </div>
  </div>
@stop