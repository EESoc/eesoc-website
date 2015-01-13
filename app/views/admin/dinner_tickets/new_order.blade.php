@extends('layouts.admin')

@section('content')
  <div class="page-header">
    <h1>Dinner Tickets</h1>
  </div>
  {{ Form::open(['action' => 'Admin\DinnerTicketsController@postConfirmation']) }}
    <div class="form-group {{ $errors->first('ic_username', 'has-error') }}">
      {{ Form::label('ic_username', 'IC Username', array('class' => 'control-label')) }}
      {{ Form::text('ic_username', null, array('class' => 'form-control input-lg')) }}
      {{ $errors->first('ic_username', '<span class="help-block">:message</span>') }}
    </div>
    <div class="form-group {{ $errors->first('quantity', 'has-error') }}">
      {{ Form::label('quantity', 'Quantity', array('class' => 'control-label')) }}
      {{ Form::text('quantity', 1, array('class' => 'form-control input-lg')) }}
      {{ $errors->first('quantity', '<span class="help-block">:message</span>') }}
    </div>
    <button type="submit" class="btn btn-primary btn-lg pull-left">
      Next
    </button>
  {{ Form::close() }}
@stop
