@extends($page->layout)

<?php $page_title = $page->name; ?>

@section($page->section)
  {{ $page->content }}
@stop