<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>EESoc TV</title>

    <!-- Bootstrap core CSS -->
    <link href="{{ asset('assets/css/bootstrap.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/animate.min.css') }}" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/jquery.slick/1.6.0/slick.css"/>
    <link href="{{ asset('assets/css/tv.css') }}" rel="stylesheet">
  </head>

  <body>
    <div class="container">
      <div class="row">
        <div class="col-lg-6 left-col text-center">
          <header>
            <img src="{{ asset('assets/images/eesoc-logo.png') }}" class="img-responsive" id="logo">
          </header>
          <div class="row">
            <div class="col-lg-6">
              <h1 id="the-time"></h1>
              <h3 id="the-date"></h3>
              <br>
              <br>
              <div id="weather"></div>
            </div>
            <div class="col-lg-6">
              <script>
                // Set path to the iframe file
                var filePath = '//www.tfl.gov.uk/tfl/syndication/feeds/serviceboard-fullscreen.htm';
                var iframe = '<iframe id="tfl_serviceboard_stretchy" name="tfl_serviceboard" src ="#" width="100%" height="1" marginheight="0" marginwidth="0" frameborder="no" scrolling="auto"></iframe>';
                document.write(iframe);

                var aspectRatio = 1.35; //Middle value to accomodate height with 3 to 4 multiple delays

                var myIframe = parent.document.getElementById("tfl_serviceboard_stretchy");
                var iframeWidth = myIframe.clientWidth - 2;
                myIframe.height = iframeWidth * aspectRatio;
                myIframe.width = iframeWidth;

                myIframe.src = filePath;
                // myIframe.style.border = "1px solid #113B92";
              </script>
            </div>
          </div>
        </div>
        <div class="col-lg-6" style="">
         
        </div>
      </div>
	  
	<div class="slideshow-container">
 <div class="slideshow">
@foreach ($files as $file)
			<div><img src="{{ asset('assets/images/slideshow/'.$file) }}" class="slideshow-img"/></div>
@endforeach	
</div>
</div>
    </div>
	

    <script src="//code.jquery.com/jquery.js"></script>
    <!-- <script src="{{{ asset('assets/js/live.js') }}}"></script> -->
    <script src="{{{ asset('assets/js/bootstrap.min.js') }}}"></script>
    <script src="{{{ asset('assets/js/moment.min.js') }}}"></script>
    <script src="{{{ asset('assets/js/jquery.simpleWeather.min.js') }}}"></script>
	<script type="text/javascript" src="//cdn.jsdelivr.net/jquery.slick/1.6.0/slick.min.js"></script>
    <script src="{{{ asset('assets/js/tv.js') }}}"></script>

  </body>
</html>
