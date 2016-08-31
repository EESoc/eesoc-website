@extends('layouts.admin')

@section('content')
  <div class="row">
    <div class="col-lg-12">
      <div class="page-header">
        <h1>New Careers Fair Company</h1>
      </div>
      {{ Form::model($stand, array('route' => 'admin.careersfair.store', 'files' => true)) }}
        @include('admin.careersfair.form')
      {{ Form::close() }}
    </div>
  </div>
@stop
