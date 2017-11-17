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
    <link href="{{{ asset('assets/css/admin.css?t=1') }}}" rel="stylesheet">

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
                  <a href="{{{ route('admin.careersfair.index') }}}">Careers Fair</a>
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
                Email
                <b class="caret"></b>
              </a>
              <ul class="dropdown-menu">
                <li>
                  <a href="{{{ route('admin.emails.index') }}}">Main List</a>
                </li>
                <!--do not use as no controller exists, also comment on href-->
                <!--li>
                  <a href=" { {# { route('admin.newsletters.index') }}}">Lists</a>
                </li-->
                <li>
                  <a href="{{{ URL::action('Barryvdh\ElfinderBundle\ElfinderController@showIndex') }}}">File Manager</a>
                </li>
              </ul>
            </li>
            <li class="{{ str_contains(Route::currentRouteAction(), 'Admin\Users') ? 'active' : '' }}">
              <a href="{{{ route('admin.users.index') }}}">Users</a>
            </li>
            <li class="{{ str_contains(Route::currentRouteAction(), 'Admin\DinnerTickets') ? 'active' : '' }}">
              <a href="{{{ action('Admin\DinnerTicketsController@getIndex') }}}">Dinner Tickets</a>
            </li>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                Logs
                <b class="caret"></b>
              </a>
              <ul class="dropdown-menu" role="menu">
                <li>
                  <a href="{{{ URL::action('Admin\SalesController@getIndex') }}}">EActivities Sales</a>
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
              <a href="{{{ action('UsersController@getDashboard') }}}">
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
        Code by <a href='&#109;&#97;&#105;&#108;&#116;&#111;&#58;&#106;&#105;&#97;&#110;&#46;&#108;&#101;&#101;&#49;&#49;&#64;&#105;&#109;&#112;&#101;&#114;&#105;&#97;&#108;&#46;&#97;&#99;&#46;&#117;&#107;'>&#74;&#105;&#97;&#110;&#32;&#89;&#117;&#97;&#110;&#32;&#76;&#101;&#101;</a>
          and <a href="https://github.com/EESoc/eesoc-website/graphs/contributors">others</a>
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
