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
        <th>Name</th>
        <th>Date</th>
        <th>Starts At</th>
        <th>Ends At</th>
        <th>Location</th>
        <th>Description</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      @foreach($events as $event)
        <tr>
          <td>{{{ $event->name }}}</td>
          <td>{{{ $event->date }}}</td>
          <td>{{{ $event->starts_at }}}</td>
          <td>{{{ $event->ends_at }}}</td>
          <td>{{{ $event->location }}}</td>
          <td>{{{ $event->description }}}</td>
          <td class="text-right">
            <a href="{{ URL::route('admin.events.edit', $event->id) }}" class="btn btn-primary btn-xs">
              <span class="glyphicon glyphicon-edit"></span>
              Edit
            </a>
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
@stop