@extends('layouts.application')

@section('content')
  <div class="page-header">
    <h1>Events</h1>
  </div>
  <div class="panel-group" id="accordion-events">
    @foreach ($events as $key => $event)
      <div class="panel panel-default">
        <div class="panel-heading">
          <h4 class="panel-title">
            <a href="#event-{{ $event->id }}" class="accordion-toggle" data-toggle="collapse" data-parent="#accordion-events">
              {{{ $event->name }}}
            </a>
          </h4>
        </div>
        <div id="event-{{ $event->id }}" class="panel-collapse collapse {{ $key === 0 ? 'in' : '' }}">
          <div class="panel-body">
            {{ $event->description }}
          </div>
        </div>
      </div>
    @endforeach
  </div>
@stop