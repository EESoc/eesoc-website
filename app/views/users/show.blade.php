@extends('layouts.application')

<?php $page_title = $user->name; ?>

@section('content')
  <div class="page-header">
    <h1>
      {{{ $user->name }}}
      <small>{{{ $user->email }}}</small>
    </h1>
  </div>
  <div class="row">
    <div class="col-lg-6">
      <pre>{{{ $user->extras }}}</pre>
    </div>
    <div class="col-lg-3">
      

    <div class="panel panel-default">
      <div class="panel-heading">
        Subscriptions
      </div>
      <div class="panel-body">
        {{ Form::open(array('action' => array('UsersController@postSubscription', $user->username))) }}
        <div class="form-group">
          @foreach ($newsletters as $newsletter)
            @if ($newsletter->is_subscribable)
            <div class="checkbox">
              <label>
                {{{ $newsletter->name }}}
                {{ Form::checkbox(
                    'newsletter_ids[]',
                    $newsletter->id,
                    in_array($user->id, $newsletter->subscribers->lists('id'))
                ) }}
              </label>
            </div>
            @endif
          @endforeach
        </div>
        <button type="submit" class="btn btn-sm btn-block" name="action" value="save" href="{{{ action('UsersController@getDashboard') }}}">
              Save
        </button>
        {{ Form::close() }}
      </div>
     
    </div>



    </div>
    <div class="col-lg-3">
    @if ( DinnerPermission::user(Auth::user())->canManageGroups())
      <a href="{{{ route('dashboard.dinner.groups.index') }}}" class="btn btn-success btn-lg btn-block">
        <span class="glyphicon glyphicon-glass"></span>
        Dinner Seating Preferences
      </a>
    @endif
      <a href="{{{ action('LockersController@getIndex') }}}" class="btn btn-info btn-lg btn-block">
        <span class="glyphicon glyphicon-tower"></span>
        Rent a Locker
      </a>
      <a href="{{{ route('dashboard.books.index') }}}" class="btn btn-info btn-lg btn-block">
        <span class="glyphicon glyphicon-book"></span>
        Buy/Sell Books
      </a>
    </div>
  </div>
@stop
