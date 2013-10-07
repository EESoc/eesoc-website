@extends('layouts.admin')

@section('content')
  <div class="row">
    <div class="col-lg-12">
      <div class="page-header">
        <h1>Editing Page</h1>
      </div>
      {{ Form::model($page, array('route' => array('admin.pages.update', $page->id), 'method' => 'patch')) }}
        @include('admin.pages.form')
      {{ Form::close() }}
      @if ($page->is_deletable)
        {{ Form::open(array('route' => array('admin.pages.destroy', $page->id), 'method' => 'delete')) }}
          <button type="submit" class="btn btn-danger btn-lg pull-right" data-confirm="Are you sure?">
            <span class="glyphicon glyphicon-remove"></span>
            Delete
          </button>
        {{ Form::close() }}
      @endif
    </div>
  </div>
@stop