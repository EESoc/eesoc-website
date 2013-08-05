<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>{{{ isset($page_title) ? $page_title.' | ' : '' }}}EESoc Administration</title>

    <!-- Bootstrap core CSS -->
    <link href="{{ asset('assets/css/bootstrap.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/bootstrap-glyphicons.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/animate.min.css') }}" rel="stylesheet">
  </head>

  <body>

    <div class="navbar">
      <div class="container">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-responsive-collapse">
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a href="#" class="navbar-brand">EESoc</a>
        <div class="nav-collapse collapse navbar-responsive-collapse">
          <ul class="nav navbar-nav">
            <li><a href="#">Posts</a></li>
            <li><a href="#">Pages</a></li>
            <li {{ str_contains(Route::currentRouteName(), 'admin.categories') ? 'class="active"' : '' }}><a href="{{ URL::route('admin.categories.index') }}">Categories</a></li>
            <li><a href="#">Users</a></li>
          </ul>
          {{ Form::open(array('action' => 'SessionsController@deleteDestroy', 'method' => 'delete', 'class' => 'navbar-form pull-right')) }}
            <button type="submit" class="btn btn-default">Sign Out</button>
          {{ Form::close() }}
          <p class="navbar-text pull-right">
            Signed in as <strong>{{ Auth::user()->name }}</strong>.
          </p>
        </div>
      </div>
    </div>
    <div class="container">

      @include('shared.flashes')
      @yield('content')

    </div> <!-- /container -->

    <script src="http://code.jquery.com/jquery.js"></script>
    <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>

  </body>
</html>