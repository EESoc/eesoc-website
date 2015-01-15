@extends('layouts.admin')

@section('content')
  <div class="row">
    <div class="col-lg-12">
      <div class="page-header">
        <h1>Editing Content</h1>
      </div>
      {{ Form::model($content, array('route' => array('admin.contents.update', $content->id), 'method' => 'patch')) }}
        @include('admin.contents.form')
      {{ Form::close() }}
      @if ($content->is_deletable)
        {{ Form::open(array('route' => array('admin.contents.destroy', $content->id), 'method' => 'delete')) }}
          <button type="submit" class="btn btn-danger btn-lg pull-right" data-confirm="Are you sure?">
            <span class="glyphicon glyphicon-remove"></span>
            Delete
          </button>
        {{ Form::close() }}
      @endif
    </div>
  </div>
@stop
