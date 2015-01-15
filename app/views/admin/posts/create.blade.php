@extends('layouts.admin')

@section('content')
  <div class="row">
    <div class="col-lg-12">
      <div class="page-header">
        <h1>New Page</h1>
      </div>
      {{ Form::model($page, array('route' => 'admin.pages.store')) }}
        @include('admin.pages.form')
      {{ Form::close() }}
    </div>
  </div>
@stop
