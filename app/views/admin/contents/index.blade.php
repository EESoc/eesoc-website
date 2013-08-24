@extends('layouts.admin')

@section('content')
  <div class="row">
    <div class="col-lg-12">
      <div class="page-header">
        <a href="{{ URL::route('admin.contents.create') }}" class="pull-right btn btn-primary btn-large">
          <span class="glyphicon glyphicon-plus"></span>
          New Content
        </a>
        <h1>Contents</h1>
      </div>
      <div class="list-group category-list">
        @foreach($contents as $content)
          <a href="{{ URL::route('admin.contents.edit', $content->id) }}" class="list-group-item">
            <h3>
              {{{ $content->name }}}
              <small>/{{{ $content->slug }}}</small>
            </h3>
          </a>
        @endforeach
      </div>
    </div>
  </div>
@stop