@extends('layouts.application')

@section('content')
<div class="page-header">
    <h1>Dinner Seating &amp; Menu Planner</h1>
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
        <span class="alert alert-info">This group belongs to you.</span>
        <br><br>
    @endif
    <div class="panel panel-default">
        <div class="panel-heading"><h4>Menu</h4></div>
        <div class="panel-body">
            <p>Please note that the menu option is fixed this year:</p>
            <h5><strong>Starter</strong></h5>
            <ul>
                <li><b>Goats Cheese, Onion and Thyme Tart</b> (beetroot and orange salad)</li>
            </ul>
            <h5><strong>Main</strong></h5>
            <ul>
                <li><b>Lemon and Garlic Marinated, Sautéed Breast of Free Range (Halal) Chicken</b> (ragout of white bean, mushrooms and cabbage in a tomato shell with olive oil mash)</li>
            </ul>
            <h5><strong>Dessert</strong></h5><ul>
                <li><strong>Raspberry Treacle Cake</strong> (rhubarb sauce, honeycomb and ice cream)</li>
            </ul>

            <p class="help-block">If you have any allergies or dietary requirements, please specify these below. If the above choices are unsuitable for your dietary requirements, we'll get in touch with you to confirm your menu. If you don't have any, please leave blank.</p>
        </div>
    </div>
    @if (!$group->members->count())
        <p><em>(Empty Group)</em></p>
    @else
        <table class='table'>
            <tr><th>#</th><th>Name</th><th>Dietary Requirements</th><th></th></tr>
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
            <!-- allow purchaser to choose menu choice for guest purchases -->
            @if ($member->ticket_purchaser_id == Auth::user()->id || Auth::user()->id == 2944 || Auth::user()->id == 2974)
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
    <input type="text" size="10" class="form-control" name="new_guest" placeholder="Your guest's name">
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
