@extends('layouts.application')

@section('body_class', 'welcome')

@section('content')
  <div class="page-header">
    <h1>{{{ $newsletter->name }}}</h1>
  </div>

  {{ Form::open(['action' => ['NewslettersController@postSignup', $newsletter->id]]) }}

    <div class="row">
      <div class="col-sm-5">
        <div class="form-group {{ $errors->first('ic_username', 'has-error') }}">
          {{ Form::label('ic_username', 'Imperial College Username', array('class' => 'control-label')) }}
          {{ Form::text('ic_username', null, array('class' => 'form-control input-lg', 'placeholder' => 'Imperial College Username')) }}
          {{ $errors->first('ic_username', '<span class="help-block">:message</span>') }}
        </div>
      </div>
      <div class="col-sm-2 text-center">
        <h1>OR</h1>
      </div>
      <div class="col-sm-5">
        <div class="form-group {{ $errors->first('email', 'has-error') }}">
          {{ Form::label('email', 'Email', array('class' => 'control-label')) }}
          {{ Form::text('email', null, array('class' => 'form-control input-lg', 'placeholder' => 'Email')) }}
          {{ $errors->first('email', '<span class="help-block">:message</span>') }}
        </div>
      </div>
    </div>

    <hr>

    <div class="form-group">
      {{ Form::submit('Sign Up', array('class' => 'btn btn-primary btn-lg btn-block')) }}
    </div>

  {{ Form::close() }}

@stop

@section('javascript_for_page')
<script>
$(function() {
  var $alert = $('.alert');

  if ($alert.length > 0) {
    setTimeout(function() {
      $alert.fadeOut();
    }, 5000);
  }
})
</script>
@stop