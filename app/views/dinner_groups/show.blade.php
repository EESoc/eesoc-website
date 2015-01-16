@extends('layouts.application')

@section('content')
<div class="page-header">
    <h1>Dinner Seating &amp; Menu Planner</h1>
</div>
<div>
    <h2>Group #{{ $group->id }}</h2>
    @if ($group->owner_id == Auth::user()->id)
        <br>
        <span class="alert alert-info">This group belongs to you.</span>
        <br><br>
    @endif
    <div class="panel panel-default">
        <div class="panel-heading"><h4>Menu Choices</h4></div>
        <div class="panel-body">
            <p>Please choose your menu options from the following choices for each of your guests:</p>
            <h5><strong>Meat Menu</strong></h5>
            <ul>
                <li><strong>Smoked haddock kedgeree cake</strong>, poached hens egg, crisp winter salad and Bombay dressing</li>
                <li><strong>Beer braised blade of beef</strong>, truffled mac ‘n’ cheese pave, roasted chantey carrots, sautéed spinach and beef cooking juices</li>
            </ul>
            <h5><strong>Vegetarian Menu</strong></h5>
            <ul>
                <li><strong>Wild mushroom and pine nuts</strong>, pickled mushroom, crispy shallots, wasabi crème, coriander cress (<strong>V</strong>)</li>
                <li><strong>Butternut squash risotto</strong>, gorgonzola, toasted walnuts, olive oil, thyme braised endive, celeriac fondant, celeriac purée(<strong>V</strong>)</li>
            </ul>
        </div>
    </div>
    @if (!$group->members->count())
        <p><em>(Empty Group)</em></p>
    @else
        <table class='table'>
            <tr><th>#</th><th>Name</th><th>Menu Choice</th><th></th></tr>
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
            @if ($member->ticket_purchaser_id == Auth::user()->id)
              @include('dinner_groups.menu_choice', ['member' => $member, 'course' => 'main'])
            @endif
            </td><td>
            @if ($member->ticket_purchaser_id == Auth::user()->id)
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
<?php $user = Auth::user(); ?>
@if ($user->unclaimed_dinner_tickets_count > 1 || ($user->dinnerGroupMember() && $user->unclaimed_dinner_tickets_count == 1))
{{Form::open(['action' => ['DinnerGroupsController@addMember']])}}
<div class="input-group col-xs-4" style="padding-left: 0;">
    <span class="input-group-addon">Add a new guest:</span>
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
    <a href="{{ route('dashboard.dinner.groups.index') }}" class="btn btn-default">Go Back</a>
</p>
@stop
