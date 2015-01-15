<?php
namespace Admin;

use \App;
use \LogStorage;
use \View;

class LogsController extends BaseController {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return View::make('admin.logs.index')
            ->with('logs', LogStorage::all());
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $file
     * @return Response
     */
    public function show($file)
    {
        $log = LogStorage::find($file);
        if ( ! $log) {
            App::abort(404, 'Log file not found');
        }

        return View::make('admin.logs.show')
            ->with('log', $log);
    }

}
