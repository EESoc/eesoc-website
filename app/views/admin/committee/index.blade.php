@extends('layouts.admin')

@section('content')
  <div class="page-header">
    <a href="{{ URL::route('admin.committee.create') }}" class="pull-right btn btn-primary btn-lg">
      <span class="glyphicon glyphicon-plus"></span>
      New Committee Member
    </a>
    <h1>Committee Members</h1>
  </div>
  <table class="table table-bordered table-hover">
    <thead>
      <tr>
        <th class="col-md-2">Logo</th>
        <th class="col-md-1">Name</th>
        <th class="col-md-1">Role</th>
        <th class="col-md-6">Description</th>
        <th class="col-md-1">URLs</th>
        <th class="col-md-1"></th>
      </tr>
    </thead>
    <tbody>
      @foreach($members as $member)
        <tr>
          <td>{{ $member->logo_image }}</td>
          <td>{{{ $member->name }}}</td>
          <td>{{ $member->role }}</td>
          <td>
            <h4>{{{ $member->short_description }}}</h4>
            <hr>
            {{ $member->description }}
          </td>
          <td>
            @if ($member->githubURL != NULL)
            <a href="{{ $member->githubURL }}" target="_blank" class="btn btn-sm btn-info btn-block">
              Github
            </a>
            <br>
            @endif
            @if ($member->facebookURL != NULL)
            <a href="{{ $member->facebookURL }}" target="_blank" class="btn btn-sm btn-info btn-block">
              Facebook
            </a>
            <br>
            @endif
            @if ($member->email != NULL)
            <a href="mailto:{{ $member->email }}" target="_blank" class="btn btn-sm btn-info btn-block">
              Email
            </a>
            @endif
          </td>
          <td>
            <a href="{{ URL::route('admin.committee.edit', $member->id) }}" class="btn btn-primary btn-block">
              <span class="glyphicon glyphicon-edit"></span>
              Edit
            </a>
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
@stop
