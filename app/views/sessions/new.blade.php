@extends('layouts.focus')

@section('content')
  <div class="col-lg-4 col-lg-offset-4">
    <div class="page-header">
      <h1>Sign In</h1>
    </div>
    {{ Form::open(array('action' => 'SessionsController@postCreate')) }}

      @include('shared.flashes')

      <div class="form-group {{ $errors->first('username', 'has-error') }}">
        {{ Form::label('username', 'IC Username', array('class' => 'control-label')) }}
        {{ Form::text('username', null, array('class' => 'form-control input-large')) }}
        {{ $errors->first('username', '<span class="help-block">:message</span>') }}
      </div>

      <div class="form-group {{ $errors->first('password', 'has-error') }}">
        {{ Form::label('password', 'Password', array('class' => 'control-label')) }}
        {{ Form::password('password', array('class' => 'form-control input-large')) }}
        {{ $errors->first('password', '<span class="help-block">:message</span>') }}
      </div>

      {{ Form::submit('Sign In', array('class' => 'btn btn-primary btn-large btn-block')) }}

    {{ Form::close() }}
  </div>
@stop