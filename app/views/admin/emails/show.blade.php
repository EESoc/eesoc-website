@extends('layouts.admin')

@section('javascript_for_page')
<script>
$(function() {
  var panel = $('[data-remote-path]'),
      remotePath = panel.data('remote-path');
  var postParams = {};
  postParams[$('[name=csrf-param]').attr('content')] = $('[name=csrf-token]').attr('content');

  function fetchPanel() {
    $.post(
      remotePath,
      postParams,
      function(response) {
        if (response.error) {
          $('.delivery-output').prepend(
            $('<p class="text-danger" />').text(response.error)
          );
        }
        
        if (response.sent_emails)
        {
          $.each(response.sent_emails, function(i, email) {
            $('.delivery-output').prepend(
              $('<p class="text-success" />').text(email + ' OK')
            );
          });

          panel.html(response.panel);

          if (response.sending) {
            // Still have more emails to send...
            fetchPanel();
          }
        }
      }
    );
  }

  fetchPanel();
});
</script>
@stop

@section('content')
  <div class="page-header">
    <h1>Editing Email</h1>
  </div>
  <div class="row">
    <div class="col-lg-9">
      <div class="panel panel-default">
        <div class="panel-heading">Details</div>
        <div class="panel-body form-horizontal">
          <dl class="dl-horizontal">
            <dt>Subject</dt>
            <dd>{{{ $email->subject }}}</dd>
            <dt>Preheader</dt>
            <dd>{{{ $email->preheader }}}</dd>
            <dt>From Name</dt>
            <dd>{{{ $email->from_name }}}</dd>
            <dt>From Email</dt>
            <dd>{{{ $email->from_email }}}</dd>
            <dt>Reply-To Email</dt>
            <dd>{{{ $email->reply_to_email }}}</dd>
          </dl>
        </div>
      </div>
      <div class="panel panel-default">
        <div class="panel-heading">Delivery Output</div>
        <div class="panel-body delivery-output"></div>
      </div>
      <div class="panel panel-default">
        <div class="panel-heading">Body</div>
        <div class="panel-body">
          <pre>{{{ $email->body }}}</pre>
        </div>
      </div>
    </div>
    <div class="col-lg-3">
      <div class="panel panel-default">
        <div class="panel-heading">Delivery</div>
        <div class="panel-body" data-remote-path="{{{ action('Admin\EmailsController@postSendBatch', $email->id) }}}">
          @include('admin.emails.send_panel_body')
        </div>
      </div>
      <div class="panel panel-default">
        <div class="panel-heading">Recipients</div>
        <div class="panel-body">
          <ul>
            @foreach ($email->newsletters as $newsletter)
              <li>{{{ $newsletter->name }}}</li>
            @endforeach
          </ul>
        </div>
      </div>
    </div>
  </div>
@stop
