@extends('layouts.admin')

@section('content')
  <div class="row">
    <div class="col-lg-12">
      <div class="page-header">
        <h1>New Content</h1>
      </div>
      {{ Form::model($content, array('route' => 'admin.contents.store')) }}
        @include('admin.contents.form')
      {{ Form::close() }}
    </div>
  </div>
@stop
