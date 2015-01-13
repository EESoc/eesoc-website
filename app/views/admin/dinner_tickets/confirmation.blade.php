@extends('layouts.admin')

@section('content')
  <div class="page-header">
    <h1>Dinner Tickets</h1>
    <h2>Please confirm:</h2>
  </div>
  <div class="row">
    <div class="col-md-6">
      <div class="panel panel-default">
        <div class="panel-heading"><h4>{{{ $user->name }}}</h4></div>
        <div class="panel-body">
          <dl>
            <dt>Email:</dt>
            <dd>{{{ $user->email }}}</dd>
            @if ($user->student_group)
              <dt>Student Group:</dt>
              <dd>{{{ $user->student_group->name }}}</dd>
            @endif
            <dt>Role:</dt>
            <dd><pre>{{{ $user->extras }}}</pre></dd>
            @if ($user->has_image)
              <dt>Photo:</dt>
              <dd>
                <a href="{{{ $user->image_url }}}">
                  <img src="data:{{ $user->image_content_type }};base64,{{ base64_encode($user->image_blob) }}" width="162" height="216" alt="{{{ $user->name }}}" class="img-thumbnail">
                </a>
              </dd>
            @endif
          </dl>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="panel panel-success">
        <div class="panel-heading">Quantity</div>
        <div class="panel-body">
          <h1>{{{ $quantity }}}x</h1>
        </div>
      </div>
      <div class="panel panel-info">
        <div class="panel-heading">Total</div>
        <div class="panel-body">
          <h1>&pound; {{{ $quantity * 27 }}}</h1>
        </div>
      </div>
      {{ Form::open(['action' => 'Admin\DinnerTicketsController@postPurchase']) }}
        {{ Form::hidden('user_id', $user->id) }}
        {{ Form::hidden('quantity', $quantity) }}
        {{ Form::submit('Confirm!', ['class' => 'btn btn-lg btn-primary']) }}
      {{ Form::close() }}
    </div>
  </div>
@stop
