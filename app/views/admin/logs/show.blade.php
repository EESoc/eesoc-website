@extends('layouts.admin')

@section('content')
  <div class="row">
    <div class="col-lg-12">
      <div class="page-header">
        <h1>
          <span class="glyphicon glyphicon-list-alt"></span>
          {{{ $log->filename }}}
        </h1>
      </div>
    </div>
  </div>
@stop

@section('body')
  @parent
  <pre style="margin: 0 15px">{{{ $log->content }}}</pre>
@stop