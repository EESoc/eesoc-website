@extends('layouts.admin')

@section('content')
  <div class="row">
    <div class="col-lg-12">
      <div class="page-header">
        <h1>Editing Link</h1>
      </div>
      {{ Form::model($link, array('route' => array('admin.links.update', $link->id), 'method' => 'patch')) }}
        @include('admin.links.form')
      {{ Form::close() }}
      {{ Form::open(array('route' => array('admin.links.destroy', $link->id), 'method' => 'delete')) }}
        <button type="submit" class="btn btn-danger btn-lg pull-right" data-confirm="{{ 'Are you sure you want to delete /' . $link->slug . '?' }}">
          <span class="glyphicon glyphicon-remove"></span>
          Delete
        </button>
      {{ Form::close() }}
    </div>
  </div>
@stop
