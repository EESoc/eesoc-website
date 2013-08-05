@extends('layouts.admin')

@section('content')
  <div class="page-header">
    <h1>New Category</h1>
  </div>
  {{ Form::model($category, array('route' => 'admin.categories.store')) }}
    @include('admin.categories.form')
  {{ Form::close() }}
@stop