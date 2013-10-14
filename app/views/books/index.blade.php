@extends('layouts.application')

@section('content')
  <div class="page-header">
    <h1>Book Sale</h1>
  </div>
  <a href="{{{ URL::action('BooksController@create') }}}">Sell Book</a>
@stop