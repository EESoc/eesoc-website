@extends('layouts.application')

@section('content')
  <div class="row">
    <div class="col-lg-12">
      <div class="page-header">
        <h1>Sell a Book</h1>
      </div>
      {{ Form::model($book, array('action' => 'BooksController@store')) }}
        @include('books.form')
      {{ Form::close() }}
    </div>
  </div>
@stop