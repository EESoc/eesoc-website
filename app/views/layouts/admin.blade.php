<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>{{{ isset($page_title) ? $page_title.' | ' : '' }}}EESoc Administration</title>

    <!-- Bootstrap core CSS -->
    <link href="{{{ asset('assets/css/bootstrap.min.css') }}}" rel="stylesheet">
    <!-- <link href="{{{ asset('assets/css/bootstrap-theme.min.css') }}}" rel="stylesheet"> -->
    <link href="{{{ asset('assets/css/animate.min.css') }}}" rel="stylesheet">
    <link href="{{{ asset('assets/css/admin.css') }}}" rel="stylesheet">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="{{{ asset('assets/js/html5shiv.js') }}}"></script>
      <script src="{{{ asset('assets/js/respond.min.js') }}}"></script>
    <![endif]-->

    <meta content="_token" name="csrf-param" />
    <meta content="{{ csrf_token() }}" name="csrf-token" />

  </head>

  <body>

    <nav class="navbar navbar-static-top navbar-default" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-responsive-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a href="{{{ url('admin') }}}" class="navbar-brand">EESoc Admin</a>
        </div>
        <div class="collapse navbar-collapse navbar-responsive-collapse">
          <ul class="nav navbar-nav">
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                Content
                <b class="caret"></b>
              </a>
              <ul class="dropdown-menu">
                <li>
                  <a href="{{{ route('admin.pages.index') }}}">Pages</a>
                </li>
                <li>
                  <a href="{{{ route('admin.contents.index') }}}">Content Blocks</a>
                </li>
                <li>
                  <a href="{{{ route('admin.events.index') }}}">Events</a>
                </li>
                <li>
                  <a href="{{{ route('admin.sponsors.index') }}}">Sponsors</a>
                </li>
                <li>
                  <a href="{{{ URL::action('Admin\InstagramPhotosController@getIndex') }}}">Instagram Photos</a>
                </li>
                <li>
                  <a href="{{{ route('admin.posts.index') }}}">Posts</a>
                </li>
                <li>
                  <a href="{{{ route('admin.categories.index') }}}">Categories</a>
                </li>
              </ul>
            </li>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                Newsletter
                <b class="caret"></b>
              </a>
              <ul class="dropdown-menu">
                <li>
                  <a href="{{{ route('admin.emails.index') }}}">Emails</a>
                </li>
                <li>
                  <a href="{{{ route('admin.newsletters.index') }}}">Lists</a>
                </li>
              </ul>
            </li>
            <li class="{{ str_contains(Route::currentRouteAction(), 'Admin\Users') ? 'active' : '' }}">
              <a href="{{{ route('admin.users.index') }}}">Users</a>
            </li>
            <li class="{{ str_contains(Route::currentRouteAction(), 'Admin\ChristmasDinnerTickets') ? 'active' : '' }}">
              <a href="{{{ action('Admin\ChristmasDinnerTicketsController@getIndex') }}}">Xmas Dinner Tickets</a>
            </li>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                Logs
                <b class="caret"></b>
              </a>
              <ul class="dropdown-menu" role="menu">
                <li>
                  <a href="{{{ route('admin.sales.index') }}}">EActivities Sales</a>
                </li>
                <li>
                  <a href="{{{ route('admin.user-sign-ins.index') }}}">User Sign Ins</a>
                </li>
                <li>
                  <a href="{{{ route('admin.logs.index') }}}">Server Logs</a>
                </li>
              </ul>
            </li>
          </ul>
          <ul class="nav navbar-nav navbar-right">
            <li>
              <a href="{{{ url('/') }}}">
                <span class="glyphicon glyphicon-home"></span>
                Main Site
              </a>
            </li>
            <li>
              <a href="{{{ action('UsersController@getShow', Auth::user()->username) }}}">
                <span class="glyphicon glyphicon-user"> </span>
                <strong>{{{ Auth::user()->name }}}</strong>
              </a>
            </li>
            <li>
              <a href="{{{ action('SessionsController@deleteDestroy') }}}" data-method="delete">
                <span class="glyphicon glyphicon-off"></span>
                Sign Out
              </a>
            </li>
          </ul>
        </div>
      </div>
    </nav>

    @section('body')
      <div class="
        container
        @if (isset($full_width) && $full_width)
          full-width
        @endif
      ">

        @include('shared.flashes')
        @yield('content')

      </div>
    @show

    <div class="container">
      <footer id="credits">
        <hr>
        &copy; {{ date('Y') }}
        Code by <a href="#">Jian Yuan Lee</a>
      </footer>
    </div>

    <script src="//code.jquery.com/jquery.js"></script>
    <script src="{{{ asset('assets/js/bootstrap.min.js') }}}"></script>
    <script src="{{{ asset('assets/js/laravel-ujs.js') }}}"></script>
    <script src="{{{ asset('assets/js/jquery.highlight.js') }}}"></script>
    <script src="{{{ asset('assets/js/jquery.slugify.js') }}}"></script>
    <script src="{{{ asset('assets/js/ckeditor/ckeditor.js') }}}"></script>
    <script src="{{{ asset('assets/js/admin.js') }}}"></script>
    @yield('javascript_for_page')

  </body>
</html>