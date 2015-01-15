@extends('layouts.admin')

@section('content')
  <div class="page-header">
    <h1>Sales</h1>
  </div>
  <table class="table table-bordered table-hover">
    <thead>
      <tr>
        <th class="text-right">ID</th>
        <th>User</th>
        <th class="text-right">Date</th>
        <th>Product</th>
        <th class="text-right">Price</th>
        <th class="text-right">Quantity</th>
        <th class="text-right">Gross Price</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($sales as $sale)
        <tr>
          <td class="text-right">{{{ $sale->id }}}</td>
          <td>
            {{{ $sale->user->name }}}
            ({{{ $sale->user->username }}})
          </td>
          <td class="text-right">{{{ $sale->date }}}</td>
          <td>
            {{{ $sale->product_name }}}
            @if ($sale->is_locker && $sale->user->has_unclaimed_lockers)
              <span class="label label-warning">
                {{{ $sale->user->unclaimed_lockers_count }}}
                Unclaimed
              </span>
            @endif
          </td>
          <td class="text-right">{{{ $sale->unit_price }}}</td>
          <td class="text-right">{{{ $sale->quantity }}}</td>
          <td class="text-right">{{{ $sale->gross_price }}}</td>
        </tr>
      @endforeach
    </tbody>
  </table>
@stop
