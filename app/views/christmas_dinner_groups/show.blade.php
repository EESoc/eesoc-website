@extends('layouts.application')

@section('content')
  <div class="page-header">
    <h1>Christmas Dinner Seating Planner</h1>
  </div>
  <p>
    <a href="{{ route('dashboard.xmas.groups.index') }}" class="btn btn-default">Go Back</a>
  </p>
  <h4 class="alert alert-info">
    You purchased <strong>{{ Auth::user()->christmasDinnerSales()->sum('quantity') }} ticket(s)</strong>.
    Please remember to add <strong>{{ Auth::user()->unclaimed_christmas_tickets_count }} more users</strong>!
  </h4>
  <div class="well">
    <h2>Group #{{ $group->id }}</h2>
    <ul>
      @foreach ($group->members as $member)
        <li>
          @if ($member->is_owner)
            <strong>
          @endif
          {{ $member->user->name }}
          @if ($member->is_owner)
            </strong>
          @endif
        </li>
      @endforeach
    </ul>
  </div>
@stop