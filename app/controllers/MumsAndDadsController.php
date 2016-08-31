<?php
/**
 * Created by PhpStorm.
 * User: Chung
 * Date: 2015-10-04
 * Time: 16:22
 */

class MumsAndDadsController extends BaseController {

    public function getIndex()
    {
        $me = Auth::user();

        $groupId = 0;
        $parentOf = $me->parent_of_family_id;
        $childOf = $me->child_of_family_id;

        if (!is_null($parentOf)){
            $groupId = $parentOf;
        }else if(!is_null($childOf)){
            $groupId = $childOf;
        }else{
            return Redirect::action('HomeController@getWelcome')
                ->with('danger', 'Sorry, you are not part of the scheme.');
        }

        $parents = User::familyParent($groupId)->get();
        $children = User::familyChild($groupId)->where('name','<>',$me->name)->get();



        $myname = explode(" ",$me->name);

       return View::make('mums_and_dads.index')
           ->with('me', $myname[0])
            ->with('parents', $parents)
            ->with('children', $children);


    }

}
