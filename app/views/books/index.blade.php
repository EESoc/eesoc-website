@extends('layouts.application')

@section('content')
  <div class="page-header">
    <a href="{{{ URL::action('BooksController@create') }}}" class="btn btn-primary btn-lg pull-right">
      <span class="glyphicon glyphicon-gbp"></span>
      Sell Book
    </a>
    <h1>Book Sale</h1>
  </div>
  <p class="lead text-center">
    Under construction.
  </p>
@stop