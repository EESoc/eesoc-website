@extends('layouts.admin')

@section('content')
  <div class="row">
    <div class="col-lg-12">
      <div class="page-header">
        <h1>New Link</h1>
      </div>
      {{ Form::model($link, array('route' => 'admin.links.store')) }}
        @include('admin.links.form')
      {{ Form::close() }}
    </div>
  </div>
@stop
