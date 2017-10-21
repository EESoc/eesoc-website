@extends('layouts.admin')

@section('content')
  <div class="row">
    <div class="col-lg-12">
      <div class="page-header">
        <h1>Editing Sponsor</h1>
      </div>
      {{ Form::model($sponsor, array('route' => array('admin.sponsors.update', $sponsor->id), 'method' => 'patch', 'files' => true)) }}
        @include('admin.sponsors.form')
      {{ Form::close() }}
      {{ Form::open(array('route' => array('admin.sponsors.destroy', $sponsor->id), 'method' => 'delete')) }}
        <button type="submit" class="btn btn-danger btn-lg pull-right" data-confirm="{{ 'Are you sure you want to delete ' . $sponsor->name . '?' }}">
          <span class="glyphicon glyphicon-remove"></span>
          Delete
        </button>
      {{ Form::close() }}
    </div>
  </div>
@stop
