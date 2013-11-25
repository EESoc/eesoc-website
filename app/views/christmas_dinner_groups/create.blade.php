@extends('layouts.application')

@section('content')
  <div class="page-header">
    <h1>Christmas Dinner Seating Planner</h1>
  </div>
  <h4 class="alert alert-info">
    You purchased <strong>{{ Auth::user()->christmasDinnerSales()->sum('quantity') }} tickets</strong>.
    Please enter their names below. They will be placed in a group.
  </h4>
  {{ Form::open(['route' => 'dashboard.xmas.groups.store']) }}
    <div class="form-group">
      {{ Form::text('user_0', Auth::user()->name, array('class' => 'form-control', 'disabled' => true)) }}
    </div>
    @for ($i = 1; $i < Auth::user()->christmas_tickets_count; $i++)
      <div class="form-group {{ $errors->first("user_{$i}", 'has-error') }}">
        {{ Form::text("user_{$i}", ($i === 0 ? Auth::user()->name : null), array('class' => 'form-control')) }}
        {{ $errors->first("user_{$i}", '<span class="help-block">:message</span>') }}
      </div>
    @endfor
    {{ Form::submit('Save', ['class' => 'btn btn-primary']) }}
  {{ Form::close() }}
@stop