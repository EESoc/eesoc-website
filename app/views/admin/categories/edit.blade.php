@extends('layouts.admin')

@section('content')
  <div class="row">
    <div class="col-lg-12">
      <div class="page-header">
        <h1>Editing Category</h1>
      </div>
      {{ Form::model($category, array('route' => array('admin.categories.update', $category->id), 'method' => 'patch')) }}
        @include('admin.categories.form')
      {{ Form::close() }}
      @if ($category->is_deletable)
        {{ Form::open(array('route' => array('admin.categories.destroy', $category->id), 'method' => 'delete')) }}
          <button type="submit" class="btn btn-danger btn-lg pull-right" data-confirm="Are you sure?">
            <span class="glyphicon glyphicon-remove"></span>
            Delete
          </button>
        {{ Form::close() }}
      @endif
    </div>
  </div>
@stop