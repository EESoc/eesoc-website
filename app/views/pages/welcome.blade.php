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
                      .attr('src', photo.standard_resolution.url)
                      .attr('width', photo.standard_resolution.width)
                      .attr('height', photo.standard_resolution.height)
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