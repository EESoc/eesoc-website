<?php
    $vName = "vegetarian_$course";
    $state = $member->$vName;
    $cls   = function($vegetarian) use ($state) { return $vegetarian == $state ? 'btn-info' : 'btn-default';};
    $menu  = function($vegetarian) use ($course)
    {
        switch ($course)
        {
        case 'starter':
            return $vegetarian ? 'Wild Mushroom' : 'Haddock';

        default:
        case 'main':
            return $vegetarian ? 'Butternut Squash Risotto' : 'Beer Braised Beef';
        }
    };
?>
<div class="btn-group" role="group">
    {{ Form::open(['action' => ['DinnerGroupsController@updateMenuChoice']]) }}
    <button type="submit" name="choice" value="meat" class="btn {{ $cls(false) }}">{{ $menu(false) }}</button>
    <button type="submit" name="choice" value="vegetarian" class="btn {{ $cls(true) }}">{{ $menu(true) }}</button>
    {{ Form::hidden('member', $member->id) }}
    {{ Form::hidden('course', $course) }}
    {{ Form::close() }}
</div>
