@extends('layouts.admin')

@section('content')
  <div class="page-header">
    <a href="{{ URL::route('admin.links.create') }}" class="pull-right btn btn-primary btn-lg">
      <span class="glyphicon glyphicon-plus"></span>
      New Link
    </a>
    <h1>Short Links</h1>
  </div>
  <div>
  <h3>Rules:</h3>
  <h4>
  <ul>
  <li>Be as concise as possible with short URLs (never include spaces!).</li>
  <li>Event-based URLs should have an expiry date (sorry, no date picker in form).</li>
  </ul>
  </h4>
  </div>
  <hr>
  <table class="table table-bordered table-hover">
    <thead>
      <tr>
        <th class="col-md-2">Short Code</th>
        <th class="col-md-6">Full URL</th>
        <th class="col-md-2">Expiry Date</th>
        <th class="col-md-2"></th>
      </tr>
    </thead>
    <tbody>
      @foreach($links as $link)
        <tr>
        <td><a href="/{{{ $link->slug }}}" target="_blank">{{{ $link->slug }}}</a></td>
          <td><a href="{{{ $link->full_url }}}" target="_blank">{{{ $link->full_url }}}</a></td>
          <td>{{ $link->expiry_date }}</td>
          <td>
            <a href="{{ URL::route('admin.links.edit', $link->id) }}" class="btn btn-primary">
              <span class="glyphicon glyphicon-edit"></span>
              Edit
            </a>
            <a href="{{{ action('Admin\LinksController@putDelete', $link->id) }}}" data-method="put" data-confirm="Are you sure?" class="btn btn-danger">
              <span class="glyphicon glyphicon-trash"></span>
              Delete
            </a>
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
@stop
