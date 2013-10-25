@extends('layouts.application')

@section('content')
  <div class="page-header">
    <h1>Events</h1>
  </div>
  <div class="row">
    <div class="col-lg-9">
      <div class="panel-group" id="accordion-events">
        @foreach ($events as $key => $event)
          <div class="panel panel-default">
            <div class="panel-heading">
              <h4 class="panel-title">
                <a href="#event-{{ $event->id }}" class="accordion-toggle" data-toggle="collapse" data-parent="#accordion-events">
                  {{{ $event->name }}}
                </a>
              </h4>
            </div>
            <div id="event-{{ $event->id }}" class="panel-collapse collapse {{ $key === 0 ? 'in' : '' }}">
              <div class="panel-body">
                {{ $event->description }}
              </div>
            </div>
          </div>
        @endforeach
      </div>
    </div>
    <div class="col-lg-3">
<?php

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

$events_calendar = [];

foreach ($events as $event) {
  $timestamp = strtotime($event->date);
  if (date('n', $timestamp) === date('n')) {
    $events_calendar[date('j', $timestamp)] = '#event-' . $event->id;
  }
}

?>
      {{ Calendar::generate(date('Y'), date('n'), $events_calendar) }}
    </div>
  </div>
@stop