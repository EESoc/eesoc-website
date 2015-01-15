@extends('layouts.admin')

@section('content')
  <div class="page-header">
    <a href="{{ URL::route('admin.sponsors.create') }}" class="pull-right btn btn-primary btn-lg">
      <span class="glyphicon glyphicon-plus"></span>
      New Sponsor
    </a>
    <h1>Sponsors</h1>
  </div>
  <table class="table table-bordered table-hover">
    <thead>
      <tr>
        <th class="col-lg-2">Logo</th>
        <th>Name</th>
        <th class="col-lg-1"></th>
      </tr>
    </thead>
    <tbody>
      @foreach($sponsors as $sponsor)
        <tr>
          <td>{{ $sponsor->logo_image }}</td>
          <td>
            <h4>{{{ $sponsor->name }}}</h4>
            {{ $sponsor->description }}
          </td>
          <td>
            <a href="{{ URL::route('admin.sponsors.edit', $sponsor->id) }}" class="btn btn-primary btn-block">
              <span class="glyphicon glyphicon-edit"></span>
              Edit
            </a>
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
@stop
