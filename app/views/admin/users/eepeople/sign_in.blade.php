@extends('layouts.admin')

@section('content')
  @include('admin.users.eepeople.header')
  {{ Form::open(array('action' => 'Admin\UsersEEPeopleController@postSignInAndPerform', 'class' => 'form-horizontal')) }}
    <fieldset>
      <legend>Sign in to EEPeople</legend>
      <div class="form-group">
        {{ Form::label('username', 'IC Username', array('class' => 'col-lg-2 control-label')) }}
        <div class="col-lg-10">
          <p class="help-block">
            {{{ Auth::user()->username }}}
          </p>
        </div>
      </div>
      <div class="form-group {{ $errors->first('password', 'has-error') }}">
        {{ Form::label('password', 'Password', array('class' => 'col-lg-2 control-label')) }}
        <div class="col-lg-10">
          {{ Form::password('password', array('class' => 'form-control')) }}
          {{ $errors->first('password', '<span class="help-block">:message</span>') }}
        </div>
      </div>
      <div class="form-group">
        <div class="col-lg-offset-2 col-lg-10">
          <button type="submit" class="btn btn-default">Sign in and Perform Synchronization</button>
        </div>
      </div>
    </fieldset>
  {{ Form::close() }}
@stop