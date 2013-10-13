@extends('layouts.admin')

@section('content')
  <div class="page-header">
    <h1>Editing Email</h1>
  </div>
  {{ Form::model($email, array('route' => array('admin.emails.update', $email->id), 'method' => 'patch')) }}
    @include('admin.emails.form')
  {{ Form::close() }}
@stop