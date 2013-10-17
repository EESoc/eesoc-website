@extends('layouts.application')

@section('body_class', 'welcome')

@section('container')

<div class="container">
  <hr class="invisible">
  <div id="photos" class="row">
    <p class="text-center">
      <img src="{{ asset('assets/images/loading.gif') }}" alt="Loading" data-loader>
    </p>
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
            $photos[firstTime ? 'append' : 'prepend'](
              $('<div class="col-lg-3" data-photo-id="' + photo.id + '" />')
                .append(
                  $('<a />')
                    .attr('href', photo.link)
                    .append(
                      $('<img />')
                        .attr('src', photo.image_standard_resolution_url)
                        .attr('alt', photo.instagram_username)
                        .attr('width', 612)
                        .attr('height', 612)
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