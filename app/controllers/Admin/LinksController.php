<?php
namespace Admin;

use \Link;
use \Input;
use \Redirect;
use \Validator;
use \View;

class LinksController extends BaseController {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return View::make('admin.links.index')
            ->with('links', Link::all());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return View::make('admin.links.create')
            ->with('link', new Link);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        $rules = [
            'slug'              => 'required',
            'full_url'          => 'required|url',
            'expiry_date'       => 'date',
        ];

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->passes()) {
            $link = new Link;
            $expiry_date = Input::get('expiry_date');
            if($expiry_date == null){
                $link->fill(array_merge(Input::all(), ['expiry_date' => null]));
            }
            else {
                $link->fill(Input::all());
            }
            $link->save();

            return Redirect::route('admin.links.index')
                ->with('success', 'Short link has been successfully created');
        } else {
            return Redirect::route('admin.links.create')
                ->withInput()
                ->withErrors($validator);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param   int $id
     * @return Response
     */
    public function edit($id)
    {
        return View::make('admin.links.edit')
            ->with('link', Link::findOrFail($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param   int $id
     * @return Response
     */
    public function update($id)
    {
        $link = Link::findOrFail($id);

        $rules = [
            'slug'              => 'required',
            'full_url'          => 'required|url',
            'expiry_date'       => 'date',
        ];
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->passes()) {
            //allow nullable expiry date, no default validator, larvel is TOO OLD
            $expiry_date = Input::get('expiry_date');
            if($expiry_date == null){
                $link->fill(array_merge(Input::all(), ['expiry_date' => null]));
            }
            else {
                $link->fill(Input::all());
            }
            $link->save();

            return Redirect::route('admin.links.index')
            ->with('success', 'Short link has been successfully created');
        } else {
            return Redirect::route('admin.links.edit', $link->id)
                ->withInput()
                ->withErrors($validator);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param   int $id
     * @return Response
     */
    public function destroy($id)
    {
        $link = Link::findOrFail($id);
        $link->delete();
        
        return Redirect::route('admin.links.index')
            ->with('success', 'Short link successfully deleted');

    }

    public function putDelete($id)
    {
        $link = Link::findOrFail($id);
        $link->delete();
        
        return Redirect::route('admin.links.index')
            ->with('success', 'Short link successfully deleted');


    }

}
