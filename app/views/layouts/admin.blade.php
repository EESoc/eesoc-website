<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>{{{ isset($page_title) ? $page_title.' | ' : '' }}}EESoc Administration</title>

    <!-- Bootstrap core CSS -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <!-- <link href="{{ asset('assets/css/bootstrap-theme.min.css') }}" rel="stylesheet"> -->
    <link href="{{ asset('assets/css/animate.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/admin.css') }}" rel="stylesheet">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="{{ asset('assets/js/html5shiv.js') }}"></script>
      <script src="{{ asset('assets/js/respond.min.js') }}"></script>
    <![endif]-->

    <meta content="_token" name="csrf-param" />
    <meta content="{{ csrf_token() }}" name="csrf-token" />

  </head>

  <body>

    <nav class="navbar navbar-default" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-responsive-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a href="#" class="navbar-brand">EESoc</a>
        </div>
        <div class="collapse navbar-collapse navbar-responsive-collapse">
          <ul class="nav navbar-nav">
            <li><a href="#">Posts</a></li>
            <li {{ str_contains(Route::currentRouteAction(), 'Admin\Pages') ? 'class="active"' : '' }}>
              <a href="{{ URL::route('admin.pages.index') }}">Pages</a>
            </li>
            <li {{ str_contains(Route::currentRouteAction(), 'Admin\Contents') ? 'class="active"' : '' }}>
              <a href="{{ URL::route('admin.contents.index') }}">Contents</a>
            </li>
            <li {{ str_contains(Route::currentRouteAction(), 'Admin\Categories') ? 'class="active"' : '' }}>
              <a href="{{ URL::route('admin.categories.index') }}">Categories</a>
            </li>
            <li {{ str_contains(Route::currentRouteAction(), 'Admin\Users') ? 'class="active"' : '' }}>
              <a href="{{ URL::route('admin.users.index') }}">Users</a>
            </li>
          </ul>
          {{ Form::open(array('action' => 'SessionsController@deleteDestroy', 'method' => 'delete', 'class' => 'navbar-form pull-right')) }}
            <button type="submit" class="btn btn-default">Sign Out</button>
          {{ Form::close() }}
          <p class="navbar-text pull-right">
            Signed in as <strong>{{ Auth::user()->name }}</strong>.
          </p>
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
    <script src="{{ asset('assets/js/jquery.highlight.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.slugify.js') }}"></script>
    <script src="{{ asset('assets/js/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('assets/js/admin.js') }}"></script>
    @yield('javascript_for_page')

  </body>
</html>