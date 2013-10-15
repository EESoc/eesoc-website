@extends('layouts.admin')

@section('content')
  <div class="page-header">
    <h1>User Sign Ins</h1>
  </div>
  <table class="table table-bordered table-hover">
    <thead>
      <tr>
        <th class="col-lg-3 text-right">Timestamp</th>
        <th class="col-lg-2">User</th>
        <th class="col-lg-1 text-right">IP Address</th>
        <th class="col-lg-6">HTTP User Agent</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($user_sign_ins as $user_sign_in)
        <tr>
          <td class="text-right">{{{ $user_sign_in->created_at->format('r') }}}</td>
          <td>{{{ $user_sign_in->user->name }}}</td>
          <td class="text-right">{{{ $user_sign_in->ip_address }}}</td>
          <td>{{{ $user_sign_in->http_user_agent }}}</td>
        </tr>
      @endforeach
    </tbody>
  </table>
@stop