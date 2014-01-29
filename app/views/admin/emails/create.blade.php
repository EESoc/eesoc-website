@extends('layouts.admin')

<?php $full_width = true; ?>

@section('content')
  <div class="page-header">
    <h1>New Email</h1>
  </div>
  {{ Form::model($email, array('route' => 'admin.emails.store')) }}
    @include('admin.emails.form')
  {{ Form::close() }}
@stop