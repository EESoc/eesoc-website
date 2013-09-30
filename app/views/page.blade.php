@extends($page->layout)

<?php $page_title = $page->name; ?>

@section('page_header')
  <div class="page-header text-center">
    <h1>{{ $page->name }}</h1>
  </div>
@stop

@section($page->section)
  {{ $page->content }}
@stop