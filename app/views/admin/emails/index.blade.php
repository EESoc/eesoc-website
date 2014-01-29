@extends('layouts.admin')

@section('content')
  <div class="row">
    <div class="col-lg-12">
      <div class="page-header">
        <a href="{{ URL::route('admin.emails.create') }}" class="pull-right btn btn-primary btn-lg">
          <span class="glyphicon glyphicon-plus"></span>
          New Email
        </a>
        <h1>Emails</h1>
      </div>
      <div class="list-group">
        @foreach($emails as $email)
          <a href="{{ URL::route('admin.emails.edit', $email->id) }}" class="list-group-item">
              {{{ $email->subject }}}
              (
                {{{ $email->sent_count }}}
                /
                {{{ $email->send_queue_length }}}
                emails sent
              )
              <span class="pull-right">
                Last updated:
                {{{ $email->updated_at }}}
              </span>
          </a>
        @endforeach
      </div>
    </div>
  </div>
@stop