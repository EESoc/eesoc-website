@extends('layouts.admin')

@section('content')
  <div class="row">
    <div class="col-lg-12">
      <div class="page-header">
        <h1>New Email</h1>
      </div>
      {{ Form::model($email, array('route' => 'admin.emails.store')) }}
        @include('admin.emails.form')
      {{ Form::close() }}
    </div>
  </div>
@stop