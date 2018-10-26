@extends('layouts.application')



<?php


/**
 * Calendar template
 * @var string
 */
$template = '
   {table_open}<table class="table table-bordered">{/table_open}

   {heading_row_start}<tr>{/heading_row_start}

   {heading_previous_cell}<th><a href="{previous_url}">&lt;&lt;</a></th>{/heading_previous_cell}
   {heading_title_cell}<th colspan="{colspan}">{heading}</th>{/heading_title_cell}
   {heading_next_cell}<th><a href="{next_url}">&gt;&gt;</a></th>{/heading_next_cell}

   {heading_row_end}</tr>{/heading_row_end}

   {week_row_start}<tr>{/week_row_start}
   {week_day_cell}<td>{week_day}</td>{/week_day_cell}
   {week_row_end}</tr>{/week_row_end}

   {cal_row_start}<tr>{/cal_row_start}
   {cal_cell_start}<td class="text-center">{/cal_cell_start}

   {cal_cell_content}<a href="{content}" data-toggle="collapse" data-parent="#accordion-events" class="label label-info">{day}</a>{/cal_cell_content}
   {cal_cell_content_today}<div class="highlight"><a href="{content}">{day}</a></div>{/cal_cell_content_today}

   {cal_cell_no_content}{day}{/cal_cell_no_content}
   {cal_cell_no_content_today}<div class="highlight">{day}</div>{/cal_cell_no_content_today}

   {cal_cell_blank}&nbsp;{/cal_cell_blank}

   {cal_cell_end}</td>{/cal_cell_end}
   {cal_row_end}</tr>{/cal_row_end}

   {table_close}</table>{/table_close}
';

Calendar::initialize([
  'template' => $template,
]);

?>

@section('container')

  @parent

<div class="container">
  <div class="row" style="margin-top: -20px;">
    <div class="col">@content('homepage-content')</div>
  </div>
  <div class="row">
    <div class="col-md-6"  style="margin-top: -5px;">
      <div style="">
        <!--a class="twitter-timeline"  href="https://twitter.com/EESoc"  data-widget-id="428571579799203840">Tweets by @EESoc</a>
        <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script-->
          <a class="twitter-timeline" data-height="560" data-dnt="true" href="https://twitter.com/EESoc/lists/imperialeee?ref_src=twsrc%5Etfw">A Twitter List by EESoc</a>
          <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
      </div>
    </div>
    <div class="col-md-6">
    <h2>Upcoming Events</h2>
            <hr>
      <div class="panel-group" id="accordion-events">
        @foreach ($events as $key => $event)
          <div class="panel {{{ $event->category ? 'panel-category-' . Str::slug($event->category->name) : 'panel-default' }}}">
            <div class="panel-heading">
              <h4 class="panel-title">
                <a href="#event-{{ $event->id }}" class="accordion-toggle" data-toggle="collapse" data-parent="#accordion-events">
                  <small>
                    {{{ $event->datetime_object->format('jS F') }}}
                  </small>
                  &mdash;
                  {{{ $event->name }}}
                </a>
                @if ($event->category)
                  <span class="label label-default pull-right">{{{ $event->category->name }}}</span>
                @endif
              </h4>
            </div>
            <div id="event-{{ $event->id }}" class="panel-collapse collapse {{ $key === 0 ? 'in' : '' }}">
              <div class="panel-body">
                {{ $event->description }}
                <dl>
                  @if ($event->location)
                    <dt>Location:</dt>
                    <dd>{{{ $event->location }}}</dd>
                  @endif
                  <dt>Date:</dt>
                  <dd>{{{ $event->datetime_object->format('F jS, Y') }}}</dd>
                  @if ($event->starts_at || $event->ends_at)
                    <dt>Time:</dt>
                    <dd>
                      {{ $event->starts_at_datetime_object->format('H:i') }}
                      @if ($event->ends_at)
                        to
                        {{ $event->ends_at_datetime_object->format('H:i') }}
                      @endif
                    </dd>
                  @endif
                </dl>
              </div>
            </div>
          </div>
        @endforeach
        <hr>
        <a href="{{ action('EventsController@getIndex', ['all' => 'yup']) }}" class="btn btn-block btn-primary">Show all events</a>
      </div>
    </div>
  </row>
</div>

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
