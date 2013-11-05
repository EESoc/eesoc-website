@if ($email->is_sending)
  <div class="progress progress-striped active">
    <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="{{{ $email->sent_email_percentage }}}" aria-valuemin="0" aria-valuemax="100" style="width: {{{ $email->sent_email_percentage }}}%;">
      <span class="sr-only">{{{ $email->sent_email_percentage }}}% Complete</span>
    </div>
  </div>
@endif

@if ($email->is_completed)
  <a href="#" class="btn btn-info disabled">Emails Sent</a>
@endif

<strong class="pull-right">
  {{{ $email->sent_count }}}
  /
  {{{ $email->send_queue_length }}}
  sent
</strong>

@if ($email->can_pause)
  <a href="{{{ action('Admin\EmailsController@putPause', $email->id) }}}" class="btn btn-danger" data-method="put">
    <span class="glyphicon glyphicon-pause"></span>
    Pause
  </a>
@endif