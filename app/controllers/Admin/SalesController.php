<?php
namespace Admin;

use \Sale;
use \View;
use \Input;
use \Redirect;
use \Artisan;
use Symfony\Component\Console\Output\StreamOutput;


class SalesController extends BaseController {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function getIndex()
    {
        return View::make('admin.sales.index')
            ->with('sales', Sale::with('user')->orderBy('date', 'desc')->get());
    }

    public function getSync()
    {
        $stream = fopen('php://temp/sync', 'r+'); //read+write mode
        Artisan::call('eactivities:sales:sync', [], new StreamOutput($stream)); //send ref to stream handle

        rewind($stream); //point handle back to beginning
        $output = stream_get_contents($stream);
        fclose($stream);

        return Redirect::action('Admin\SalesController@getIndex')
            ->with('success', 'Sales has been successfully synced.<br>Output:<br>' . $output);
    }
}
