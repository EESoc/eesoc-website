@extends('layouts.focus')

@section('content')
  <h1 class="text-center" style="margin-top: 200px">Redirecting to ICU Shop Page&hellip;</h1>
@stop

@section('javascript_for_page')
<script type="text/javascript">
  setTimeout(function() {
    window.location.href = '{{{ $redirect_to }}}';
  }, 2000);
</script>
@stop
