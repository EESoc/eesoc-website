@extends('layouts.admin')

@section('content')
  <div class="row">
    <div class="col-lg-12">
      <div class="page-header">
        <h1>New Event</h1>
      </div>
      {{ Form::model($event, array('route' => 'admin.events.store')) }}
        @include('admin.events.form')
      {{ Form::close() }}
    </div>
  </div>
@stop