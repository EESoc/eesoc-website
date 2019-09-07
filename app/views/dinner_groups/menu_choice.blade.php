<?php

    $name = ( $member->name == Auth::user()->name ? "Yourself" : $member->name);

?>
<div class="form-horizontal">

    {{ Form::open(['action' => ['DinnerGroupsController@updateMenuChoice']]) }}
<?php

    /**

    <button type="submit" name="choice" value="meat" class="btn {{ $cls(false) }}">{{ $menu(false) }}</button>
    <button type="submit" name="choice" value="vegetarian" class="btn {{ $cls(true) }}">{{ $menu(true) }}</button>
    {{ Form::hidden('course', $course) }}

     **/
    ?>

        <div class="form-group">
            <!--label for="special_req" class="col-sm-2 control-label" style="text-align: left; top: -8px;">Dietary Requirements</label-->
            <div class="col-md-6 col-sm-10">
                <input type="text" name="special_req" id="special_req" value="{{ $member->special_req }}" maxlength="140" style="width: 90%"/>
            </div>
            <div class="col-md-6 col-sm-10">
                <button type="submit" class="btn btn-success btn-sm" style="margin-top: -3px;">Update Choices for {{ $name }}</button>
            </div>


            
        </div>


    

    {{ Form::hidden('member', $member->id) }}
    {{ Form::close() }}
</div>
</div>