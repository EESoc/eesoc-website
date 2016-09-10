<?php

class TVController extends BaseController {

    public function show()
    {
		
		$files = array();
		if ($handle = opendir('assets/images/slideshow')) {

			while (false !== ($entry = readdir($handle))) {

				if ($entry != "." && $entry != "..") {

					$files[] = "$entry";
				}
			}

			closedir($handle);
		}

        return View::make('tv.show', array("files" => $files) );
    }

}
