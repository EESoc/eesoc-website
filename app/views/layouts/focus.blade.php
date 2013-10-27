<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>{{{ isset($page_title) ? $page_title.' | ' : '' }}}EESoc</title>

    <!-- Bootstrap core CSS -->
    <link href="{{ asset('assets/css/bootstrap.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/animate.min.css') }}" rel="stylesheet">
  </head>

  <body>

    <div class="container">

      @yield('content')

    </div> <!-- /container -->

    <script src="http://code.jquery.com/jquery.js"></script>
    <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
    @yield('javascript_for_page')
    @include('shared.gosquared')

  </body>
</html>