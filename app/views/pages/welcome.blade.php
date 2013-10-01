@extends('layouts.application')

@section('body_class', 'welcome')

@section('container')

<div class="container">
  <hr class="invisible">
  <div id="photos" class="row">
    <p class="text-center">
      <img src="{{ asset('assets/images/loading.gif') }}" alt="Loading">
    </p>
  </div>
</div>

@parent

@stop

@section('javascript_for_page')
<script type="text/javascript">
$(function() {
  var $photos = $('#photos');
  function load_video() {
    $.get(
      '{{ url('home/photos') }}',
      function(data) {
        $photos.empty();
        $.each(data, function(i, photo) {
          $photos.append(
            $('<div class="col-lg-3" />')
              .append(
                $('<a />')
                  .attr('href', photo.link)
                  .append(
                    $('<img />')
                      .attr('src', photo.image_standard_resolution_url)
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
        });
      }
    );
  }

  setInterval(load_video, 5000);
  load_video();
});
</script>
@stop