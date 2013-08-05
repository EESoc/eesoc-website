@extends('layouts.admin')

@section('content')
  <div class="row">
    <div class="col-lg-12">
      <div class="page-header">
        <div class="pull-right btn-group">
          <a href="#" class="btn btn-default">
            <span class="glyphicon glyphicon-download-alt"></span>
            Fetch from eActivities
          </a>
          <a href="#" class="btn btn-default">
            <span class="glyphicon glyphicon-refresh"></span>
            Synchronize Users
          </a>
          <a href="{{ URL::route('admin.categories.create') }}" class="btn btn-default">
            <span class="glyphicon glyphicon-plus"></span>
            New User
          </a>
        </div>
        <h1>Users</h1>
      </div>
      <ul class="nav nav-pills">
        <li class="active">
          <a href="{{ URL::route('admin.users.index') }}">
            <span class="badge pull-right">{{ $everybody_count }}</span>
            Everybody
          </a>
        </li>
        <li>
          <a href="{{ URL::route('admin.users.index', array('filter' => 'admins')) }}">
            <span class="badge pull-right">{{ $admins_count }}</span>
            Admins
          </a>
        </li>
        <li>
          <a href="{{ URL::route('admin.users.index', array('filter' => 'non-admins')) }}">
            <span class="badge pull-right">{{ $non_admins_count }}</span>
            Non-Admins
          </a>
        </li>
        <li><a href="{{ URL::route('admin.users.index', array('filter' => 'members')) }}">Members</a></li>
        <li><a href="{{ URL::route('admin.users.index', array('filter' => 'non-members')) }}">Non-Members</a></li>
      </ul>
      <hr>
      <table class="table table-striped table-hover">
        <thead>
          <tr>
            <th>#</th>
            <th>Username</th>
            <th>Name</th>
            <th class="text-center">Admin?</th>
            <th class="text-center">Signed In At Least Once?</th>
          </tr>
        </thead>
        @foreach($users as $user)
          <tr>
            <td>{{ $user->id }}</td>
            <td>{{{ $user->username }}}</td>
            <td>{{{ $user->name }}}</td>
            <td class="text-center">
              @if ($user->isAdmin())
                <span class="glyphicon glyphicon-ok text-success"></span>
              @endif
            </td>
            <td class="text-center">
              @if ($user->last_sign_in_at === null)
                <span class="glyphicon glyphicon-remove text-danger"></span>
              @else
                <span class="glyphicon glyphicon-ok text-success"></span>
              @endif
            </td>
          </tr>
        @endforeach
      </table>
    </div>
  </div>
@stop