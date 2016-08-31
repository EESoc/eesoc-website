@extends('layouts.admin')

@section('content')
  <div class="row">
    <div class="col-lg-12">
      <div class="page-header">
        <h1>Editing Careers Fair Company</h1>
      </div>
      {{ Form::model($stand, array('route' => array('admin.careersfair.update', $stand->id), 'method' => 'patch', 'files' => true)) }}
        @include('admin.careersfair.form')
      {{ Form::close() }}
      {{ Form::open(array('route' => array('admin.careersfair.destroy', $stand->id), 'method' => 'delete')) }}
        <button type="submit" class="btn btn-danger btn-lg pull-right" data-confirm="Are you sure?">
          <span class="glyphicon glyphicon-remove"></span>
          Delete
        </button>
      {{ Form::close() }}
    </div>
  </div>
@stop
