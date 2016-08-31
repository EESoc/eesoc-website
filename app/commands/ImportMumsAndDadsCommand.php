<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ImportMumsAndDadsCommand extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'family:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import from a CSV file, the grouping of mums and dads families. Col: child, parent1, parent2';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {


        //Import CSV
        $row = 0;
        $group = 0;
        $parents = array("","");

        if (($handle = fopen($this->argument("path"), "r")) !== FALSE) {

            User::resetFamilies();
            $this->info('Resetted the Mums and Dads on file.');

            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $row++; //Next row

                //Skip the headers
                if ($row == 1){
                    //Check assertion
                    if (count($data) != 3){
                        $this->error('The CSV files has '.count($data).' fields instead of 3.' );
                        return;
                    }

                    continue;
                }

                //Clean input
                for($i=0; $i < count($data); $i++){
                    $data[$i] = trim($data[$i]);
                }

                //next group if parents are different
                if ( $data[1] != $parents[0] || $data[2] != $parents[1]){

                    //Inc group id
                    $group++;

                    //Importing the parent
                    for($i=0; $i < 2; $i++) {
                        //Writing to the current group parents variable.
                        $parents[$i] = $data[$i + 1];
                    }

                    $this->info("Family {$group}: ".$parents[0]." & ".$parents[1]);

                    for($i=0; $i < 2; $i++){
                        $parent = User::where('name', '=', $parents[$i])->firstOrFail();

                        $parent->parent_of_family_id = $group;
                        $parent->save();

                    }


                }

                $child = User::where('name', '=', $data[0])->firstOrFail();

                $child->child_of_family_id = $group;
                $child->save();

                $this->info(" ++ ".$child->name);

            }

            $this->info('Import of '.$group.' Families completed successfully.');

            fclose($handle);
        }else{
            $this->error('Cannot open the csv file at '.$this->argument(path).'!');
            return;
        }


    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return array(
            array('path', InputArgument::REQUIRED, 'Path to CSV file'),
        );
    }

}
