@extends($page->layout)

<?php $page_title = $page->name; ?>

@section($page->section)
  <div class="page-header text-center">
    <h1>{{ $page->name }}</h1>
  </div>
  {{ $page->content }}
@stop