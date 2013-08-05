@extends('layouts.admin')

@section('content')
  <div class="page-header">
    <a href="{{ URL::route('admin.categories.create') }}" class="pull-right btn btn-primary btn-large">
      <span class="glyphicon glyphicon-plus"></span>
      New Category
    </a>
    <h1>Categories</h1>
  </div>
  <div class="list-group">
    @foreach($categories as $category)
      <a href="{{ URL::route('admin.categories.edit', $category->id) }}" class="list-group-item">
        <h4>{{ $category->name }}</h4>
        {{{ $category->description }}}
      </a>
    @endforeach
  </div>
@stop