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
      <div class="list-group category-list">
        @foreach ($log->content as $log)
          <a href="#" class="list-group-item log-item">
            {{{ $log['name'] }}}
            <pre class="hide">{{{ implode("\n", $log['details']) }}}</pre>
          </a>
        @endforeach
      </div>
    </div>
  </div>
@stop

@section('javascript_for_page')
<script>
$(function() {
  $('.log-item').click(function() {
    $(this).find('pre').toggleClass('hide');
    return false;
  });
});
</script>
@stop
