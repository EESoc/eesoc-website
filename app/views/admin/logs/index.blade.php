@extends('layouts.admin')

@section('content')
  <div class="row">
    <div class="col-lg-12">
      <div class="page-header">
        <h1>Logs</h1>
      </div>
      <div class="list-group category-list">
        @foreach($logs as $log)
          <a href="{{{ URL::route('admin.logs.show', $log->filename) }}}" class="list-group-item">
            <span class="glyphicon glyphicon-list-alt"></span>
            {{{ $log->filename }}}
          </a>
        @endforeach
      </div>
    </div>
  </div>
@stop
