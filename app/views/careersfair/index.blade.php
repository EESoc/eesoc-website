@extends('layouts.application')

<?php $page_title = 'Careers Fair'; ?>

@section('javascript_for_page')
  <script>
    $(function() {
      $('[data-stand-show]').click(function() {
        var $this = $(this),
                standId = $this.data('stand-show'),
                standName = $this.data('stand-name');

        $('#modal-'+standId).modal();
        return false;
      });
    });
  </script>
@stop

@section('content')
  <div class="page-header">
    <h1>Careers Fair</h1>
    <p>Click on the logo to learn more about each of the company at our careers fair.</p>
  </div>
  <div class="row sponsors">
    @foreach ($stands as $stand)
      <div class="col-lg-3">
        <a href="#" data-stand-show="{{ $stand->id }}" data-stand-name="{{{ $stand->name }}}">
          {{ $stand->logo_image }}
        </a>
      </div>
    @endforeach
  </div>

  @foreach ($stands as $stand)
          <!-- Modal {{ $stand->id }} -->
  <div class="modal fade" id="modal-{{ $stand->id }}" tabindex="-1" role="dialog" aria-labelledby="modallabel-{{ $stand->id }}">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="modallabel-{{ $stand->id }}">{{{ $stand->name }}}</h4>
        </div>
        <div class="modal-body">


          <p><strong>Interested in {{{ $stand->interested_groups_list }}} students.</strong></p>

          {{ $stand->description }}
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
  @endforeach


@stop
