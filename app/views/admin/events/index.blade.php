@extends('layouts.admin')

@section('content')
  <div class="page-header">
    <a href="{{ URL::route('admin.events.create') }}" class="pull-right btn btn-primary btn-lg">
      <span class="glyphicon glyphicon-plus"></span>
      New Event
    </a>
    <h1>Events</h1>
  </div>
  <table class="table table-bordered table-hover">
    <thead>
      <tr>
        <th>Date</th>
        <th>Name</th>
        <th>Starts At</th>
        <th>Ends At</th>
        <th>Location</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      @foreach($events as $event)
        <tr
          class="
            @if ($event->hidden)
              danger
            @endif
            @if ( ! $event->date)
              warning
            @endif
          "
        >
          <td>{{{ $event->date }}}</td>
          <td>{{{ $event->name }}}</td>
          <td>{{{ $event->starts_at }}}</td>
          <td>{{{ $event->ends_at }}}</td>
          <td>{{{ $event->location }}}</td>
          <td class="text-right">
            @if ($event->hidden)
              <a href="{{{ action('Admin\EventsController@putVisibility', [$event->id, 'unhide']) }}}" data-method="put" class="btn btn-warning">Unhide</a>
            @else
              <a href="{{{ action('Admin\EventsController@putVisibility', [$event->id, 'hide']) }}}" data-method="put" class="btn btn-danger">Hide</a>
            @endif
            <a href="{{ route('admin.events.edit', $event->id) }}" class="btn btn-primary">
              <span class="glyphicon glyphicon-edit"></span>
              Edit
            </a>
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
@stop