<?php

interface Loggable {

    public function info($string);
    public function comment($string);
    public function question($string);
    public function error($string);

}