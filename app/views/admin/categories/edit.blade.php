@extends('layouts.admin')

@section('content')
  <div class="page-header">
    <h1>Editing Category</h1>
  </div>
  {{ Form::model($category, array('route' => array('admin.categories.update', $category->id), 'method' => 'put')) }}
    @include('admin.categories.form')
  {{ Form::close() }}
  @if ($category->is_deletable())
    {{ Form::open(array('route' => array('admin.categories.destroy', $category->id), 'method' => 'delete')) }}
      <button type="submit" class="btn btn-danger btn-large pull-right">
        <span class="glyphicon glyphicon-remove"></span>
        Delete
      </button>
    {{ Form::close() }}
  @endif
@stop