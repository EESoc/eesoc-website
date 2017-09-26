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

		$isParent = false;
		
        if (!is_null($parentOf)){
            $groupId = $parentOf;
			$isParent = true;
        }else if(!is_null($childOf)){
            $groupId = $childOf;
        }else{
            return Redirect::action('HomeController@getWelcome')
                ->with('danger', 'Sorry, you are not part of the scheme.');
        }

        $parents = User::familyParent($groupId)->get();
        $children = User::familyChild($groupId)->where('name','<>',$me->name)->get();

		$roomtext = "";
		
		if ($groupId >= 1 && $groupId <= 14){
			$roomtext = "403a";
		}else if ($groupId >= 15 && $groupId <= 26){
			$roomtext = "403b";
		}else if ($groupId >= 27 && $groupId <= 40){
			$roomtext = "406";
		}else if ($groupId >= 41 && $groupId <= 52){
			$roomtext = "407a";
		}
		

        $myname = explode(" ",$me->name);

       return View::make('mums_and_dads.index')
           ->with('me', $myname[0])
            ->with('group', $groupId)
            ->with('room', $roomtext)
            ->with('isParent', $isParent)
            ->with('parents', $parents)
            ->with('children', $children);


    }

}
