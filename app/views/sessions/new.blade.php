@extends('layouts.focus')

@section('content')
  <div class="col-lg-4 col-lg-offset-4 text-center">
    <div class="page-header">
      <h1><span class="glyphicon glyphicon-lock"></span></h1>
      <h2>Sign In</h2>
    </div>
    {{ Form::open(array('action' => 'SessionsController@postCreate')) }}

      @include('shared.flashes')

      <div class="form-group {{ $errors->first('username', 'has-error') }}">
        {{ Form::label('username', 'Imperial College Username', array('class' => 'control-label')) }}
        {{ Form::text('username', null, array('class' => 'form-control input-lg', 'placeholder' => 'e.g. abc123')) }}
        {{ $errors->first('username', '<span class="help-block">:message</span>') }}
      </div>

      <div class="form-group {{ $errors->first('password', 'has-error') }}">
        {{ Form::label('password', 'Password', array('class' => 'control-label')) }}
        {{ Form::password('password', array('class' => 'form-control input-lg')) }}
        {{ $errors->first('password', '<span class="help-block">:message</span>') }}
      </div>

      <div class="checkbox text-left">
        <label>
          {{ Form::checkbox('remember_me', 'true', true) }}
          Remember Me?
        </label>
      </div>

      {{ Form::submit('Sign In', array('class' => 'btn btn-primary btn-large btn-block', 'data-loading-text' => 'Signing in&hellip;')) }}

    {{ Form::close() }}
  </div>
@stop