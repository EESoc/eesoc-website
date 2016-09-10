<?php

use Robbo\Presenter\Presenter;

class CareersFairStandPresenter extends Presenter {

    public function presentLogoPath()
    {
        if ( ! empty($this->logo)) {
            return asset('assets/images/careersfair/' . $this->logo);
        }
    }

    public function presentLogoImage()
    {
        if ( ! empty($this->logo)) {
            return '<img src="' . $this->logo_path . '" alt="' . htmlspecialchars($this->name) . '" class="img-responsive">';
        }
    }

    public function presentInterestedGroupsList()
    {
        $array = $this->getInterestedGroups();

        for($i = 1;  $i <= 6; $i++){
            if (!isset($array[$i])){
                return "";
            }

            if ($array[$i] === 1 || $array[$i] === true){
                $array[$i] = "Yes";
            }else if ($array[$i] === 0 || $array[$i] === false){
                $array[$i] = "No";
            }
        }

        $output = array("first year" => $array[1],
            "second year" => $array[2],
            "third year" => $array[3],
            "graduate" => $array[4],
            "MSc" => $array[5],
            "PhD" => $array[6]);


        $out = "";

        foreach($output as $group => $string){
            if ($string == "Yes"){
                $out .= $group . ", ";
            }else if ($string != "No"){
                $out .= $group . ": " . $string . ", ";
            }
        }

        $out = substr($out, 0, -2);

        return $out;
    }

}
