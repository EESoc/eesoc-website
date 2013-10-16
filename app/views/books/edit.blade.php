@extends('layouts.application')

@section('content')
  <div class="row">
    <div class="col-lg-12">
      <div class="page-header">
        <h1>Editing Book Listing</h1>
      </div>
      {{ Form::model($book, array('route' => array('dashboard.books.update', $book->id), 'method' => 'patch')) }}
        @include('books.form')
      {{ Form::close() }}
    </div>
  </div>
@stop