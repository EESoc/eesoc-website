@extends('layouts.application')

@section('body_class', 'welcome')

@section('container')

<div class="container">
  <div class="row" style="margin-top: -20px;">
    <div class="col-md-6">
      @content('homepage-content')
      <h4>Instagram <strong>#eesoc</strong></h4>
      <hr>
      <div id="photos" class="row">
        <p class="text-center">
          <img src="{{ asset('assets/images/loading.gif') }}" alt="Loading" data-loader>
        </p>
      </div>
    </div>
    <div class="col-md-6">
      <div style="margin-top: 65px;">
        <a class="twitter-timeline"  href="https://twitter.com/EESoc"  data-widget-id="428571579799203840">Tweets by @EESoc</a>
        <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
      </div>
    </div>
  </div>
</div>

@parent

@stop

@section('javascript_for_page')
<script type="text/javascript">
$(function() {
  var $photos = $('#photos');
  var firstTime = true;
  function load_video() {
    $photos.find('p:has([data-loader])').remove();
    $.get(
      '{{ url('home/photos') }}',
      function(data) {
        $.each(data, function(i, photo) {
          if ($('[data-photo-id=' + photo.id + ']').length === 0) {
            console.log(photo)
            $photos[firstTime ? 'append' : 'prepend'](
              $('<div class="col-md-3 col-sm-4 col-xs-6" data-photo-id="' + photo.id + '" />')
                .append(
                  $('<a />')
                    .attr('href', photo.link)
                    .append(
                      $('<img />')
                        .attr('src', photo.image_thumbnail_url)
                        .attr('alt', photo.instagram_username)
                        .attr('width', 150)
                        .attr('height', 150)
                        .addClass('img-responsive')
                    )
                )
                .append(
                  $('<hr />')
                    .addClass('invisible')
                )
            );
          }
        });
        firstTime = false;
      }
    );
  }

  setInterval(load_video, 5000);
  load_video();
});
</script>
@stop
