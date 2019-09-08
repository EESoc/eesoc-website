<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ImportBooksISBNCommand extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'books:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import from a CSV file, a list of ISBN for EESoc Book Sales';

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

        $google = false;
		$amazon= false;
		
		$responsible = User::where('id', '=', 1291)->firstOrFail(); //Sautrik Banerjee of EESoc 2015s


        $file = $this->argument("path");
        $this->info("Reading from list: ".$file);
$failed = array();
        //Import CSV
        $row = 0;
        if (($handle = fopen($file,'r')) !== FALSE) {

            $this->info('File opened: '.$file.'.');

            while (($data = fgetcsv($handle, 20, ",")) !== FALSE) {
                $row++; //Next row

                //Clean input
                for($i=0; $i < count($data); $i++){
                    $data[$i] = trim($data[$i]);
                }

				$this->info($row." : Searching ISBN: ".$data[0]);
				
				if (count($data) > 1){
						$this->info("Metadata discarded: ".$data[1]);
				}




                if ($google){

                    $this->info('Searching on Google '.$data[0]);
                    $search = file_get_contents("https://www.googleapis.com/books/v1/volumes?q=isbn:".urlencode($data[0])."&key=AIzaSyABWOEVCQ83nB81klr3V_6i6XmGE9Oiz04&country=UK&maxResults=1");

                    $results = json_decode($search, true);

                    //['google_book_id', 'thumbnail', 'isbn', 'name', 'condition', 'target_student_groups', 'target_course', 'price', 'quantity', 'contact_instructions', 'expires_at'];

                    if (isset($results['items'][0])) {
                        $return = $results['items'][0];
                        $book = new Book;
                        $book->user()->associate($responsible);
                        $book->google_book_id = $return['id'];
                        if (isset($return['volumeInfo']['imageLinks'])) {

                            $book->thumbnail = $return['volumeInfo']['imageLinks']['thumbnail'];
                        }
                        $book->isbn = $data[0];
                        $book->name = $return['volumeInfo']['title'];
                        $book->condition = "Used";
                        $book->target_student_groups = "EESoc Book Sales";
                        //$book->target_course = "";
                        $book->price = 25.00;
                        $book->quantity = 1;
                        $book->contact_instructions = "This is part of the EESoc Book Sales, please contact sautrik.banerjee13@imperial.ac.uk for more information.";
                        $book->expires_at = "2016-08-01";

                        $book->save();


                        $this->info('...Import of '.$book->name.' completed successfully.');
                    }else {
                        $failed[] = $data[0];

                        $this->error('...failed!!!!' . " Info:\r\n" . $search);
                    }
                }else if ($amazon){
                    $this->info('Searching on Amazon ' . $data[0]);

                    /*
                     *
                     * http://webservices.amazon.com/onca/xml?
  Service=AWSECommerceService
  &Operation=ItemLookup
  &ResponseGroup=Large
  &SearchIndex=All
  &IdType=ISBN
  &ItemId=076243631X
  &AWSAccessKeyId=[Your_AWSAccessKeyID]
  &AssociateTag=[Your_AssociateTag]
  &Timestamp=[YYYY-MM-DDThh:mm:ssZ]
  &Signature=[Request_Signature]
                     *
                     * */

                    $private_key = "l3XC2Kft00cja2VZyOpNt79I4jxnRXYUaBeBHzM9";
                    $params = array();
                    $method = "GET";
                    $host = "webservices.amazon.com";
                    $uri = "/onca/xml";

// additional parameters
                    $params["Service"] = "AWSECommerceService";
                    $params["Operation"] = "ItemLookup";
                    $params["ResponseGroup"] = "Large";
                    $params["SearchIndex"] = "All";
                    $params["IdType"] = "ISBN";
                    $params["ItemId"] = $data[0];

                    $params["AWSAccessKeyId"] = "AKIAI4OV6KZBMHKOS6MQ";
                    $params["AssociateTag"] = "e03ef-21";
// GMT timestamp
                    $params["Timestamp"] = gmdate("Y-m-d\TH:i:s\Z");
                    $params["Version"] = "2013-08-01";

// sort the parameters
                    uksort($params, 'strcmp');
// create the canonicalized query
                    $canonicalized_query = array();
                    foreach ($params as $param => $value) {
                        $param = str_replace("%7E", "~", rawurlencode($param));
                        $value = str_replace("%7E", "~", rawurlencode($value));
                        $canonicalized_query[] = $param . "=" . $value;
                    }
                    $canonicalized_query = implode("&", $canonicalized_query);

// create the string to sign
                    $string_to_sign = $method . "\n" . $host . "\n" . $uri . "\n" . $canonicalized_query;

// calculate HMAC with SHA256 and base64-encoding
                    $signature = base64_encode(hash_hmac("sha256", $string_to_sign, $private_key, True));

// encode the signature for the request
                    $signature = str_replace("%7E", "~", rawurlencode($signature));

                    $url = "http://" . $host . $uri . "?" . $canonicalized_query . "&Signature=" . $signature;


                    $retry = false;
                    $tries = 0;
                   do {

                       // $search = file_get_contents($url);
                       /* Use CURL to retrieve the data so that http errors can be examined */
                       $ch = curl_init($url);
                       curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                       curl_setopt($ch, CURLOPT_TIMEOUT, 7);
                       $search = curl_exec($ch);
                       $curl_info = curl_getinfo($ch);
                       curl_close($ch);

                       if($curl_info['http_code']==200){
                           $retry = false;
                       }else{
                           $this->error("Lookup HTTP req failed. See");
                           var_dump($curl_info, $search);

                           $tries++;
                           if ($tries <= 6) {
                               $this->info("Retrying in " . pow(2, $tries));
                               sleep(pow(2, $tries));
                           }else{
                               $this->error($data[0]." SKIPPED.");
                               $failed[] = $data[0];

                               continue 2;
                           }
                       }

                   }while($retry === false);


                    $item = new SimpleXMLElement($search);

                    $json_string = json_encode($item);

                    $results = json_decode($json_string, TRUE);


                    //['google_book_id', 'thumbnail', 'isbn', 'name', 'condition', 'target_student_groups', 'target_course', 'price', 'quantity', 'contact_instructions', 'expires_at'];

                    if (isset($results['Items']['Item'][0])) {
                        $return = $results['Items']['Item'][0];
                    } elseif (isset($results['Items']['Item'])) {
                        $return = $results['Items']['Item'];
                    } else {
                        $failed[] = $data[0];
                        $this->error('...failed!!!!' . " Info:\r\n" . $search);

                        continue;
                    }

                    $book = new Book;
                    $book->user()->associate($responsible);
                    //$book->google_book_id = $return['id'];
                    if (isset($return['LargeImage'])) {

                        $book->thumbnail = $return['LargeImage']['URL'];
                    }
                    $book->isbn = $data[0];
                    $book->name = $return['ItemAttributes']['Title'];
                    $book->condition = "Used";
                    $book->target_student_groups = "EESoc Book Sales";
                    //$book->target_course = "";
                    $book->price = 25.00;
                    $book->quantity = 1;
                    if (isset($return['ItemAttributes']['Author'])) {

                        if (is_array($return['ItemAttributes']['Author'])) {
                            $book->authors = implode(", ", $return['ItemAttributes']['Author']);
                        } else {
                            $book->authors = $return['ItemAttributes']['Author'];

                        }
                    }else if (isset($return['ItemAttributes']['Manufacturer']) && is_string($return['ItemAttributes']['Manufacturer'])) {
                        $book->authors = $return['ItemAttributes']['Manufacturer'];
                    }


                        $book->contact_instructions = "This is part of the EESoc Book Sales, please contact sautrik.banerjee13@imperial.ac.uk for more information. For more details about this book, please visit ".$return['DetailPageURL']." on Amazon.";
                        if (isset($return['EditorialReviews']['EditorialReview']['Content'])){
                            $book->contact_instructions .= "\r\n\r\n".$return['EditorialReviews']['EditorialReview']['Content'];
                        }

                        $book->expires_at = "2016-08-01";

                        $book->save();


                        $this->info('...Import of '.$book->name.' completed successfully.');
                        sleep(3);

                }else{
					//http://isbndb.com/api/v2/json/39ZFTDCD/book/ISBN
					//39ZFTDCD
					$this->info('Searching on ISBNDB ' . $data[0]);

					$url = "http://isbndb.com/api/v2/json/39ZFTDCD/book/".$data[0];

                    $retry = false;
                    $tries = 0;
                   do {

                       // $search = file_get_contents($url);
                       /* Use CURL to retrieve the data so that http errors can be examined */
                       $ch = curl_init($url);
                       curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                       curl_setopt($ch, CURLOPT_TIMEOUT, 7);
                       $search = curl_exec($ch);
                       $curl_info = curl_getinfo($ch);
                       curl_close($ch);

                       if($curl_info['http_code']==200){
                           $retry = false;
                       }else{
                           $this->error("Lookup HTTP req failed. See");
                           var_dump($curl_info, $search);

                           $tries++;
                           if ($tries <= 6) {
                               $this->info("Retrying in " . pow(2, $tries));
                               sleep(pow(2, $tries));
							   $retry = true;
                           }else{
                               $this->error($data[0]." SKIPPED.");
                               $failed[] = $data[0];
							$retry = false;
                               continue 2;
                           }
                       }

                   }while($retry === true);

                    $results = json_decode($search, TRUE);

					//['google_book_id', 'thumbnail', 'isbn', 'name', 'condition', 'target_student_groups', 'target_course', 'price', 'quantity', 'contact_instructions', 'expires_at'];

                    if (isset($results['data'][0])) {
                        $return = $results['data'][0];
                    } else {
                        $failed[] = $data[0];
                        $this->error('...failed!!!!' . " Info:\r\n" . $search);

                        continue;
                    }

                    $book = new Book;
                    $book->user()->associate($responsible);
                    //$book->google_book_id = $return['id'];
                    //$book->thumbnail = $return['LargeImage']['URL'];
                    
                    $book->isbn = $data[0];
                    $book->name = $return['title'];
                    $book->condition = "Used";
                    $book->target_student_groups = "EESoc Book Sales";
                    //$book->target_course = "";
                    $book->price = 25.00;
                    $book->quantity = 1;
                    if (isset($return['author_data']['name'])) {
                            $book->authors = $return['author_data']['name'];
                    }else if (isset($return['publisher_name'])) {
                        $book->authors = $return['publisher_name'];
                    }
                     
					 $book->contact_instructions = "This is part of the EESoc Book Sales, please contact sautrik.banerjee13@imperial.ac.uk for more information.";


                        $book->expires_at = "2016-08-01";

                        $book->save();


                        $this->info('...Import of '.$book->name.' completed successfully.');
                        sleep(1);
				}
				
                

            }

            fclose($handle);
            $this->error('Failed:');
            foreach ($failed as $isbn){
                $this->error($isbn);

            }
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