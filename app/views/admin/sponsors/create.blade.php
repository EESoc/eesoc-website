@extends('layouts.admin')

@section('content')
  <div class="row">
    <div class="col-lg-12">
      <div class="page-header">
        <h1>New Sponsor</h1>
      </div>
      {{ Form::model($sponsor, array('route' => 'admin.sponsors.store', 'files' => true)) }}
        @include('admin.sponsors.form')
      {{ Form::close() }}
    </div>
  </div>
@stop
