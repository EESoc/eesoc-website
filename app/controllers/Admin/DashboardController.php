<?php
namespace Admin;

use \Sale;
use \View;
use \Product;

class DashboardController extends BaseController {

    public function getShow()
    {
        $date_str = date('Y-m-d');
        $month_number = date('m'); // get the $request->input('month') here 
        $current_year = date('Y');
        #print_r($month_number + " " + $current_year);
        $number_of_days = 14;
        #$number_of_days = date('t');
        # make this for each product rather than manual variables!!
        
        $results_lockers = array();
        $results_bar_night = array();
        $date_labels = array();

        // for ($i=1; $i<=$number_of_days; $i++){
        //   $new_date = date_sub(date_create($date_str), date_interval_create_from_date_string($i . ' days')); #date($current_year . '-' . $month_number . '-' . $i);
        //   $new_date_str= date_format($new_date, 'Y-m-d');
        //   $results_lockers []= Sale::where('date', '=', $new_date_str)
        //                     ->where('product_id', '=', Product::ID_EESOC_DINNER)
        //                     ->sum('quantity'); // counting number of sales per day

        //   $results_bar_night []= Sale::where('date', '=', $new_date_str)
        //   ->where('product_id', '=', Product::ID_EESOC_DINNER_NON_MEMBER)
        //   ->sum('quantity');
          
        //   #echo "<h1>$count_perday | $date_str | $new_date_str</h1>";
          
        //   $date_labels []= date('jS M',strtotime($new_date_str));
        // }

        $prod_labels = array();

        //only show valid products
        $prod_ids = array_keys (
                        array_filter(Product::is_product_syncable(), function ($is_syncable_value) {
                            return ($is_syncable_value == 1);
                        })
                    );

        $prod_count = array();

        foreach ($prod_ids as $prod_id) {
            $count = 0;
            $sales = Sale::where('product_id', '=', $prod_id)->get();

            foreach ($sales as $sale) {
                $count += $sale['quantity'];
            }

            if($count > 0){
                $prod_label = str_replace("EESoc ", "", Sale::where('product_id', '=', $prod_id)->first()->product_name);
                $prod_labels []= $prod_label;
                $prod_count []= $count;
            }
        }

        
        
        $list = ['date_labels' => $date_labels,
                 'results_lockers' => $results_lockers,
                 'results_bar_night' => $results_bar_night,
                 'prod_labels' => $prod_labels,
                 'prod_count' => $prod_count
                ];
        return View::make('admin.dashboard')->with('list', $list);
    }

}
