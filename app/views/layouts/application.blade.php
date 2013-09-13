<!-- <!DOCTYPE html> -->
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>{{{ isset($page_title) ? $page_title.' | ' : '' }}}EESoc</title>

    <!-- Bootstrap core CSS -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <!-- <link href="{{ asset('assets/css/bootstrap-theme.min.css') }}" rel="stylesheet"> -->
    <link href="{{ asset('assets/css/animate.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/admin.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/user.css') }}" rel="stylesheet">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="{{ asset('assets/js/html5shiv.js') }}"></script>
      <script src="{{ asset('assets/js/respond.min.js') }}"></script>
    <![endif]-->

    <meta content="_token" name="csrf-param" />
    <meta content="{{ csrf_token() }}" name="csrf-token" />

  </head>

  <body @yield('body')>

    <nav class="navbar navbar-static-top navbar-inverse" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-responsive-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a href="{{ URL::to('/') }}" class="navbar-brand">EESoc</a>
        </div>
        <div class="collapse navbar-collapse navbar-responsive-collapse">
          <ul class="nav navbar-nav">
            <li><a href="{{ URL::to('/about') }}">About Us</a></li>
            <li><a href="#">News &amp; Events</a></li>
            <li><a href="#">Sponsors</a></li>
            <li><a href="#">Committee</a></li>
          </ul>
          @if (Auth::check())
            <ul class="nav navbar-nav navbar-right">
              <li class="navbar-text">
                Signed in as
              </li>
              <li>
                <a href="{{{ URL::action('UsersController@getShow', Auth::user()->username) }}}">
                  <strong>{{{ Auth::user()->name }}}</strong>
                </a>
              </li>
              <li>
                <a href="#">
                  <span class="glyphicon glyphicon-wrench"></span>
                </a>
              </li>
              <li>
                <a href="{{{ URL::action('SessionsController@deleteDestroy') }}}" data-method="delete">
                  <span class="glyphicon glyphicon-off"></span>
                </a>
              </li>
            </ul>
          @else
            <a href="{{{ URL::action('SessionsController@getNew') }}}" class="btn btn-default navbar-btn navbar-right">
              Sign In
            </a>
          @endif
        </div>
      </div>
    </nav>
    <div class="container">

      @include('shared.flashes')
      @yield('content')

      <div class="row">
        <footer id="credits" class="col-lg-12">
          <hr>
          &copy; {{ date('Y') }}
          Code by <a href="#">Jian Yuan Lee</a>
        </footer>
      </div>
    </div>

    <script src="//code.jquery.com/jquery.js"></script>
    <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/laravel-ujs.js') }}"></script>
    @yield('javascript_for_page')

  </body>
</html>