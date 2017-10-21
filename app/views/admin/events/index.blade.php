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
        <th>Category</th>
        <th>Starts At</th>
        <th>Ends At</th>
        <th>Location</th>
        <th style="width: 160px;"></th>
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
          <td>
            @if ($event->category)
              {{{ $event->category->name }}}
            @endif
          </td>
          <td>{{{ $event->starts_at }}}</td>
          <td>{{{ $event->ends_at }}}</td>
          <td>{{{ $event->location }}}</td>
          <td class="text-right" style="width: 160px;">
            @if ($event->hidden)
              <a href="{{{ action('Admin\EventsController@putVisibility', [$event->id, 'unhide']) }}}" data-method="put" class="btn-sm btn-success">
                <span class="glyphicon glyphicon-eye-open"></span>
              </a>
            @else
              <a href="{{{ action('Admin\EventsController@putVisibility', [$event->id, 'hide']) }}}" data-method="put" class="btn-sm btn-danger">
                <span class="glyphicon glyphicon-eye-close"></span>
              </a>
            @endif
            &nbsp;
            <a href="{{{ action('Admin\EventsController@putDelete', $event->id) }}}" data-method="put" data-confirm="Are you sure?" class="btn-sm btn-danger">
              <span class="glyphicon glyphicon-trash"></span>
            </a>
            <a href="{{ route('admin.events.edit', $event->id) }}" class="btn btn-sm">
              <span class="glyphicon glyphicon-edit"></span>
              Edit
            </a>
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
@stop
