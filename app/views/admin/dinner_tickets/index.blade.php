@extends('layouts.admin')

@section('content')
  <div class="page-header">
    <h1>Dinner Tickets</h1>
    @if (Auth::user()->id == 2944)
    <a href="{{{ action('Admin\DinnerTicketsController@getNew') }}}" class="btn btn-lg btn-primary">New Order</a>
    @endif
    <a href="{{ action('Admin\SalesController@getSync') }}" class="btn btn-lg btn-info" >Sync Sales</a>
  </div>
  <table class="table table-bordered">
    <thead>
      <tr>
        <th>ID</th>
        <th>User</th>
        <th>Quantity</th>
        <th>Date/Time</th>
        <th>Origin</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($sales as $sale)
        <tr>
          <td>{{ $sale->id }}</td>
          <td>
            {{{ $sale->user->name }}}
            <small>
              (Username: {{{ $sale->user->username }}})
            </small>
            @if ($sale->user->getUnclaimedDinnerTicketsCountAttribute())
              <span class="label label-warning">
                {{{ $sale->user->unclaimed_dinner_tickets_count }}}
                Unclaimed
              </span>
            @endif
          </td>
          <td>{{ $sale->quantity }}</td>
          <td>{{ $sale->created_at }}</td>
          <td>{{ $sale->origin }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>
@stop
