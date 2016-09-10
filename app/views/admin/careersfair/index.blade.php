@extends('layouts.admin')

@section('content')
  <div class="page-header">
    <a href="{{ URL::route('admin.careersfair.create') }}" class="pull-right btn btn-primary btn-lg">
      <span class="glyphicon glyphicon-plus"></span>
      New Stand
    </a>
    <h1>Careers Fair Stands</h1>
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
      @foreach($stands as $stand)
        <tr>
          <td>{{ $stand->logo_image }}</td>
          <td>
            <h4>{{{ $stand->name }}}</h4>

            <p><strong>Interested in {{{ $stand->interested_groups_list }}}</strong></p>

            {{ $stand->description }}
          </td>
          <td>
            <a href="{{ URL::route('admin.careersfair.edit', $stand->id) }}" class="btn btn-primary btn-block">
              <span class="glyphicon glyphicon-edit"></span>
              Edit
            </a>
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
@stop
