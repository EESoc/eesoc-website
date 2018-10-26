@extends('layouts.admin')

@section('content')
  <div class="row">
    <div class="col-lg-12">
      <div class="page-header">
        <h1>New Committee Member</h1>
      </div>
      {{ Form::model($member, array('route' => 'admin.committee.store', 'files' => true)) }}
        @include('admin.committee.form')
      {{ Form::close() }}
    </div>
  </div>
@stop
