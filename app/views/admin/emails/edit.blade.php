@extends('layouts.admin')

@section('content')
  <div class="row">
    <div class="col-lg-12">
      <div class="page-header">
        <h1>Editing Email</h1>
      </div>
      {{ Form::model($email, array('route' => array('admin.emails.update', $email->id), 'method' => 'patch')) }}
        @include('admin.emails.form')
      {{ Form::close() }}

      {{ Form::open(array('route' => array('admin.emails.destroy', $email->id), 'method' => 'delete')) }}
        <button type="submit" class="btn btn-danger btn-lg pull-right" data-confirm="Are you sure?">
          <span class="glyphicon glyphicon-remove"></span>
          Delete
        </button>
      {{ Form::close() }}
    </div>
  </div>
@stop