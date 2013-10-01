@extends('layouts.admin')

@section('content')
  <div class="row">
    <div class="col-lg-12">
      <div class="page-header">
        <a href="{{ URL::route('admin.pages.create') }}" class="pull-right btn btn-primary btn-lg">
          <span class="glyphicon glyphicon-plus"></span>
          New Page
        </a>
        <h1>Pages</h1>
      </div>
      <div class="list-group category-list">
        @foreach($pages as $page)
          <a href="{{ URL::route('admin.pages.edit', $page->id) }}" class="list-group-item">
            <h3>
              {{{ $page->name }}}
              <small>/{{{ $page->slug }}}</small>
            </h3>
          </a>
        @endforeach
      </div>
    </div>
  </div>
@stop