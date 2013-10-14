@extends('layouts.application')

@section('content')
  <div class="page-header">
    <h1>
      {{{ $user->name }}}
      <small>{{{ $user->email }}}</small>
    </h1>
  </div>
  <div class="row">
    <div class="col-lg-3">
      <a href="{{{ action('LockersController@getIndex') }}}" class="btn btn-primary btn-lg btn-block">
        <span class="glyphicon glyphicon-tower"></span>
        Rent a Locker
      </a>
    </div>
    <div class="col-lg-9">
      <pre>{{{ $user->extras }}}</pre>
    </div>
  </div>
@stop