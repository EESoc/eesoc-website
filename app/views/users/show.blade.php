@extends('layouts.application')

@section('content')
  <div class="page-header">
    <h1>
      {{{ $user->name }}}
      <small>{{{ $user->email }}}</small>
    </h1>
  </div>
  <div class="row">
    <div class="col-lg-8">
      <pre>{{{ $user->extras }}}</pre>
    </div>
    <div class="col-lg-4">
      <a href="{{{ action('LockersController@getIndex') }}}" class="btn btn-info btn-lg btn-block">
        <span class="glyphicon glyphicon-tower"></span>
        Rent a Locker
      </a>
      <a href="{{{ route('dashboard.books.create') }}}" class="btn btn-info btn-lg btn-block">
        <span class="glyphicon glyphicon-book"></span>
        Sell Books
      </a>
    </div>
  </div>
@stop