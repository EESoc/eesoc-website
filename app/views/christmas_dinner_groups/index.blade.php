@extends('layouts.application')

@section('content')
  <div class="page-header">
    <h1>Christmas Dinner Seating Planner</h1>
  </div>
  @if (ChristmasPermission::user(Auth::user())->canCreateNewGroup())
    <p>
      <a href="{{ route('dashboard.xmas.groups.create') }}" class="btn btn-primary">Create New Group</a>
    </p>
  @endif
  @foreach ($groups as $group)
    <div class="well well-sm">
      <h4>
        <a href="{{ route('dashboard.xmas.groups.show', $group->id) }}">
          Group #{{ $group->id }}
        </a>
      </h4>
      <ul>
        @foreach ($group->members as $member)
          <li>
            @if ($member->is_owner)
              <strong>
            @endif
            {{ $member->name }}
            @if ($member->is_owner)
              </strong>
            @endif
          </li>
        @endforeach
      </ul>
    </div>
  @endforeach
  <p>If you experience any issues, please <a href='mailto:christos.karpis11@imperial.ac.uk'>email us</a></p>
@stop