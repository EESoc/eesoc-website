@extends('layouts.application')

@section('javascript_for_page')
  <script>
    $(function() {
      $('[data-sponsor-show]').click(function() {
        var $this = $(this),
            sponsorId = $this.data('sponsor-show'),
            sponsorName = $this.data('sponsor-name');

        GoSquared.q.push(['TrackEvent', 'Clicked on a Sponsor', {
          sponsorName: sponsorName
        }]);

        $('[data-sponsor-id]').addClass('hide');
        $('[data-sponsor-id=' + sponsorId + ']').removeClass('hide');

        return false;
      });
    });
  </script>
@stop

@section('content')
  <div class="row">
    <div class="col-lg-6">
      <div class="page-header">
        <h1>Sponsors</h1>
      </div>
      <div class="row sponsors">
        @foreach ($sponsors as $sponsor)
          <div class="col-lg-4">
            <a href="#" data-sponsor-show="{{ $sponsor->id }}" data-sponsor-name="{{{ $sponsor->name }}}">
              {{ $sponsor->logo_image }}
            </a>
          </div>
        @endforeach
      </div>
    </div>
    <div class="col-lg-6">
      @foreach ($sponsors as $sponsor)
        <div class="hide" data-sponsor-id="{{ $sponsor->id }}">
          <div class="page-header">
            <h2>{{{ $sponsor->name }}}</h2>
          </div>
          {{ $sponsor->description }}
        </div>
      @endforeach
    </div>
  </div>
@stop