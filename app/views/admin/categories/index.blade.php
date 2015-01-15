@extends('layouts.admin')

@section('content')
  <div class="row">
    <div class="col-lg-12">
      <div class="page-header">
        <a href="{{ URL::route('admin.categories.create') }}" class="pull-right btn btn-primary btn-lg">
          <span class="glyphicon glyphicon-plus"></span>
          New Category
        </a>
        <h1>Categories</h1>
      </div>
      <div class="list-group category-list">
        @foreach($categories as $category)
          <a href="{{ URL::route('admin.categories.edit', $category->id) }}" class="list-group-item">
            <h3>
              {{{ $category->name }}}
              <small>/{{{ $category->slug }}}</small>
            </h3>
          </a>
        @endforeach
      </div>
    </div>
  </div>
@stop
