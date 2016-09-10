@extends('layouts.application')

<?php $page_title = 'Sponsors'; ?>

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

       // $('[data-sponsor-id]').addClass('hide');
       // $('[data-sponsor-id=' + sponsorId + ']').removeClass('hide');

	   $('#modal-'+sponsorId).modal();
        return false;
      });
    });
  </script>
@stop

@section('content')
      <div class="page-header">
        <h1>Sponsors</h1>
		<p>Click on the logo to learn more about each of our sponsors.<p></p>If you are interested in becoming a sponsor to reach out to our members, please get in touch with <a href="mailto:eesoc.ilo@imperial.ac.uk">our Industrial Liaison Officers</a>.</p>
      </div>
      <div class="row sponsors">
        @foreach ($sponsors as $sponsor)
          <div class="col-lg-3">
            <a href="#" data-sponsor-show="{{ $sponsor->id }}" data-sponsor-name="{{{ $sponsor->name }}}">
              {{ $sponsor->logo_image }}
            </a>
          </div>
        @endforeach
      </div>
   
   @foreach ($sponsors as $sponsor)
	   <!-- Modal {{ $sponsor->id }} -->
		<div class="modal fade" id="modal-{{ $sponsor->id }}" tabindex="-1" role="dialog" aria-labelledby="modallabel-{{ $sponsor->id }}">
		  <div class="modal-dialog" role="document">
			<div class="modal-content">
			  <div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="modallabel-{{ $sponsor->id }}">{{{ $sponsor->name }}}</h4>
			  </div>
			  <div class="modal-body">
				{{ $sponsor->description }}
			  </div>
			  <div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			  </div>
			</div>
		  </div>
		</div>
      @endforeach
    
	
@stop
