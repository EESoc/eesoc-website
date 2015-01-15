@extends('layouts.admin')

@section('content')
  <div class="row">
    <div class="col-lg-12">
      <div class="page-header">
        <h1>Editing Event</h1>
      </div>
      {{ Form::model($event, array('route' => array('admin.events.update', $event->id), 'method' => 'patch')) }}
        @include('admin.events.form')
      {{ Form::close() }}
      {{ Form::open(array('route' => array('admin.events.destroy', $event->id), 'method' => 'delete')) }}
        <button type="submit" class="btn btn-danger btn-lg pull-right" data-confirm="Are you sure?">
          <span class="glyphicon glyphicon-remove"></span>
          Delete
        </button>
      {{ Form::close() }}
    </div>
  </div>
@stop
