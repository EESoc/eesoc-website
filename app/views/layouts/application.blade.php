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
    <link href="{{ asset('assets/css/application.css') }}" rel="stylesheet">
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

  <body class="@yield('body_class')">

    @section('navbar')
      <div id="site-header">
        <nav class="navbar navbar-static-top navbar-inverse hidden-xs" role="navigation">
          <div class="container">
            <ul class="nav navbar-nav navbar-right">
              @if (Auth::check())
                <li class="navbar-text">
                  Signed in as
                </li>
                <li>
                  <a href="{{{ action('UsersController@getShow', Auth::user()->username) }}}">
                    <strong>{{{ Auth::user()->name }}}</strong>
                  </a>
                </li>
                <li>
                  <a href="{{{ action('UsersController@getDashboard') }}}">
                    <span class="glyphicon glyphicon-wrench"></span>
                    Settings
                  </a>
                </li>
                <li>
                  <a href="{{{ action('SessionsController@deleteDestroy') }}}" data-method="delete">
                    <span class="glyphicon glyphicon-off"></span>
                    Sign Out
                  </a>
                </li>
              @else
                <a href="{{{ action('SessionsController@getNew') }}}" class="btn btn-primary">
                  <span class="glyphicon glyphicon-lock"></span>
                  Sign In
                </a>
              @endif
            </ul>
          </div>
        </nav>
        <nav class="navbar navbar-static-top navbar-default" role="navigation">
          <div class="container">
            <div class="navbar-header">
              <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
              </button>
              <a class="navbar-brand" href="{{ url('/') }}">
                <img src="{{ asset('assets/images/eesoc.png') }}" alt="EESoc" height="100">
              </a>
            </div>
            <div class="collapse navbar-collapse">
              <ul class="nav navbar-nav visible-xs">
                @if (Auth::check())
                  <li>
                    <a href="{{{ action('UsersController@getShow', Auth::user()->username) }}}">
                      <strong>{{{ Auth::user()->name }}}</strong>
                    </a>
                  </li>
                  <li>
                    <a href="{{{ action('UsersController@getDashboard') }}}">
                      <span class="glyphicon glyphicon-wrench"></span>
                      Settings
                    </a>
                  </li>
                  <li>
                    <a href="{{{ action('SessionsController@deleteDestroy') }}}" data-method="delete">
                      <span class="glyphicon glyphicon-off"></span>
                      Sign Out
                    </a>
                  </li>
                @else
                  <a href="{{{ action('SessionsController@getNew') }}}" class="btn btn-primary">
                    <span class="glyphicon glyphicon-lock"></span>
                    Sign In
                  </a>
                @endif
              </ul>
              <ul class="nav navbar-nav navbar-right">
                <li class="{{ Request::is('/') ? 'active' : '' }}">
                  <a href="{{ url('/') }}">
                    <span class="glyphicon glyphicon-chevron-right"></span> Home
                  </a>
                </li>
                <li class="{{ Request::is('about') ? 'active' : '' }}">
                  <a href="{{ url('about') }}">
                    <span class="glyphicon glyphicon-chevron-right"></span> About Us
                  </a>
                </li>
                <li class="{{ Request::is('events') ? 'active' : '' }}">
                  <a href="{{ url('events') }}">
                    <span class="glyphicon glyphicon-chevron-right"></span> Events
                  </a>
                </li>
                <li class="{{ Request::is('sponsors') ? 'active' : '' }}">
                  <a href="{{ url('sponsors') }}">
                    <span class="glyphicon glyphicon-chevron-right"></span> Sponsors
                  </a>
                </li>
                <li class="{{ Request::is('committee') ? 'active' : '' }}">
                  <a href="{{ url('committee') }}">
                    <span class="glyphicon glyphicon-chevron-right"></span> Committee
                  </a>
                </li>
              </ul>
            </div><!-- /.navbar-collapse -->
          </div>
        </nav>
      </div>

      <header id="old-site-header" class="hide">
        <div class="container">
          <div class="row">
            <div class="col-lg-6 col-left">
              <a href="{{ url('/') }}">
                <img src="{{ asset('assets/images/eesoc.png') }}" alt="EESoc" class="brand img-responsive">
              </a>
              <div class="row">
                <div class="col-lg-7">
                  <nav role="navigation">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-responsive-collapse">
                      <span class="sr-only">Toggle navigation</span>
                      <span class="icon-bar"></span>
                      <span class="icon-bar"></span>
                      <span class="icon-bar"></span>
                    </button>
                    <div class="collapse navbar-collapse navbar-responsive-collapse">
                      <ul class="nav nav-pills nav-main nav-stacked">
                        <li <?php if (Request::is('/')) echo 'class="active"' ?>>
                          <a href="{{ url('/') }}">
                            <span class="glyphicon glyphicon-chevron-right"></span> Home
                          </a>
                        </li>
                        <li <?php if (Request::is('about')) echo 'class="active"' ?>>
                          <a href="{{ url('about') }}">
                            <span class="glyphicon glyphicon-chevron-right"></span> About Us
                          </a>
                        </li>
                        <li>
                          <a href="{{ url('events') }}">
                            <span class="glyphicon glyphicon-chevron-right"></span> Events
                          </a>
                        </li>
                        <li>
                          <a href="{{ url('sponsors') }}">
                            <span class="glyphicon glyphicon-chevron-right"></span> Sponsors
                          </a>
                        </li>
                        <li>
                          <a href="{{ url('committee') }}">
                            <span class="glyphicon glyphicon-chevron-right"></span> Committee
                          </a>
                        </li>
                      </ul>
                    </div>
                  </nav>
                </div>
              </div>
            </div>
            <div class="col-lg-6 col-right">
              <div class="clearfix text-right">
                @if (Auth::check())
                  <ul class="nav navbar-nav navbar-right">
                    <li class="navbar-text">
                      Signed in as
                    </li>
                    <li>
                      <a href="{{{ action('UsersController@getShow', Auth::user()->username) }}}">
                        <strong>{{{ Auth::user()->name }}}</strong>
                      </a>
                    </li>
                    <li>
                      <a href="{{{ action('UsersController@getDashboard') }}}">
                        <span class="glyphicon glyphicon-wrench"></span>
                        Settings
                      </a>
                    </li>
                    <li>
                      <a href="{{{ action('SessionsController@deleteDestroy') }}}" data-method="delete">
                        <span class="glyphicon glyphicon-off"></span>
                        Sign Out
                      </a>
                    </li>
                  </ul>
                @else
                  <a href="{{{ action('SessionsController@getNew') }}}" class="btn btn-primary">
                    <span class="glyphicon glyphicon-lock"></span>
                    Sign In
                  </a>
                @endif
              </div>

              @yield('page_header')
            </div>
          </div>
        </div>
      </header>

<?php /*
      <nav class="navbar navbar-static-top navbar-inverse" role="navigation">
        <div class="container">
          <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-responsive-collapse">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
            <a href="{{ URL::to('/') }}" class="navbar-brand">
              <img src="{{ asset('assets/images/eesoc.png') }}" alt="EESoc" class="img-responsive">
            </a>
          </div>
          <div class="collapse navbar-collapse navbar-responsive-collapse">
            <ul class="nav nav-stacked">
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
    @show
*/ ?>

    @section('container')
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
    @show

    <script src="//code.jquery.com/jquery.js"></script>
    <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/laravel-ujs.js') }}"></script>
    @yield('javascript_for_page')
    @include('shared.gosquared')

  </body>
</html>