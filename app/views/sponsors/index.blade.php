@extends('layouts.application')

@section('content')
  <div class="page-header">
    <h1>Sponsors</h1>
  </div>
  <div class="row sponsors">
    @foreach ($sponsors as $sponsor)
      <div class="col-lg-3">
        <a href="#">
          {{ $sponsor->logo_image }}
        </a>
      </div>
    @endforeach
  </div>
@stop