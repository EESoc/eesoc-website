@extends('layouts.admin')

@section('content')
  <div class="row">
    <div class="col-lg-12">
      <div class="page-header">
        <a href="{{ URL::route('admin.posts.create') }}" class="pull-right btn btn-primary btn-lg">
          <span class="glyphicon glyphicon-plus"></span>
          New Post
        </a>
        <h1>Posts</h1>
      </div>
      <div class="list-group category-list">
        @foreach($posts as $post)
          <a href="{{ URL::route('admin.posts.edit', $post->id) }}" class="list-group-item">
            <h3>
              {{{ $post->name }}}
              <small>/{{{ $post->slug }}}</small>
            </h3>
          </a>
        @endforeach
      </div>
    </div>
  </div>
@stop
