@extends('layouts.application')

@section('content')
<div class="page-header">
    <h1>EESoc Trip Room Selection</h1>
</div>
<div>
    <h2>Group #{{ $group->id }}</h2>
    @if ($group->is_full)
        <br>
        <span class="alert alert-danger" style="color:red;">This group is now full, to add yourself or your guest, please select another group.</span>
        <br><br>
    @endif
    @if ($group->owner_id == Auth::user()->id)
        <br>
        <span class="alert alert-info">You may not leave this group as it belongs to you.</span>
        <br><br>
    @endif
    
    @if (!$group->members->count())
        <p><em>(Empty Group)</em></p>
    @else
        <table class='table'>
            <tr><th>#</th><th>Name</th><th>Any Requirements</th><th></th></tr>
        @foreach ($group->members as $i => $member)
            <tr>
            <td>{{$i + 1}}</td>
            <td>
            @if ($member->is_owner)
                <strong>
            @endif
                {{ $member->name }}
            @if ($member->is_owner)
                </strong>
            @endif
            </td><td>

            <!-- allow admins to see dietary requirementes TODO: Change this every year-->
            <!-- currently only 2944 (), 2974 & 2918 are allowed to help prevent data misuse -->
            @if ($member->ticket_purchaser_id == Auth::user()->id || Auth::user()->id == 2944 || Auth::user()->id == 2974 || Auth::user()->id == 2918)

              @include('dinner_groups.menu_choice', ['member' => $member])
            @endif
            </td><td>

             <!-- allow purchaser to remove guests from group as long they are NOT AN OWNER -->
            @if ($member->ticket_purchaser_id == Auth::user()->id && (!$member->is_owner || DinnerGroup::CAN_LEAVE_OWN_GRP))
                {{Form::open(['action' => ['DinnerGroupsController@removeMember']])}}
                <button type="submit" name="remove" value="{{$member->id}}"  class="btn btn-danger">Remove</button>
                {{Form::close()}}
            @endif
            </td>
            </tr>
        @endforeach
        </table>
    @endif
    <hr>
</div>
<p>
<?php $user = Auth::user(); $user->getUnclaimedDinnerTicketsCountAttribute(); ?>
@if (!$group->is_full)
@if ($user->unclaimed_dinner_tickets_count > 1 || ($user->dinnerGroupMember && $user->unclaimed_dinner_tickets_count == 1))
{{Form::open(['action' => ['DinnerGroupsController@addMember']])}}
<div class="input-group col-xs-4" style="padding-left: 0; top: 1px;">
    <span class="input-group-addon">Add a new guest (non-member):</span>

    <input type="text" size="10" style="width: 360px;" class="form-control" name="new_guest" placeholder="Your guest's name">

    <input type="hidden" name="group" value="{{$group->id}}">
</div>
<button type="submit" class="btn btn-primary">Add guest</button>
{{Form::close()}}<br>
@endif
  @if (DinnerPermission::user(Auth::user())->canJoinGroup($group))
    {{ Form::model($group, array('route' => array('dashboard.dinner.groups.update', $group->id), 'method' => 'patch')) }}
      {{ Form::hidden('user_id', Auth::user()->id) }}
      {{ Form::submit('Join this Group', ['class' => 'btn btn-primary']) }}
    {{ Form::close() }}
    <br>
  @endif
  @if (DinnerPermission::user(Auth::user())->canLeaveGroup($group))
    {{ Form::model($group, array('route' => array('dashboard.dinner.groups.destroy', $group->id), 'method' => 'delete')) }}
      {{ Form::hidden('user_id', Auth::user()->id) }}
      {{ Form::submit('Leave this Group', ['class' => 'btn btn-danger']) }}
    {{ Form::close() }}
    <br>
  @endif
@endif
    <a href="{{ route('dashboard.dinner.groups.index') }}" class="btn btn-default">Go Back</a>
</p>
@stop