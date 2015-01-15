<?php
namespace Admin;

use \Sale;
use \View;

class SalesController extends BaseController {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return View::make('admin.sales.index')
            ->with('sales', Sale::with('user')->get());
    }

}
