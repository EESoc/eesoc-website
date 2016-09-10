<?php
namespace Admin;

use \CareersFairStand;
use \Input;
use \Redirect;
use \Validator;
use \View;

class CareersFairController extends BaseController {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return View::make('admin.careersfair.index')
            ->with('stands', CareersFairStand::all());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return View::make('admin.careersfair.create')
            ->with('stand', new CareersFairStand);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        $rules = [
            'name'        => 'required',
            'description' => 'required',
            'logo'        => 'required|image|mimes:jpeg,bmp,png',
            'position'    => 'numeric',
            'interested_groups' => 'required'
        ];

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->passes()) {
            $stand = new CareersFairStand;
            $stand->fill(Input::all());
            $stand->save();

            return Redirect::route('admin.careersfair.index')
                ->with('success', 'This careers fair company has been successfully created');
        } else {
            return Redirect::route('admin.careersfair.create')
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
        return View::make('admin.careersfair.edit')
            ->with('stand', CareersFairStand::findOrFail($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param   int $id
     * @return Response
     */
    public function update($id)
    {
        $stand = CareersFairStand::findOrFail($id);

        $rules = [
            'name'        => 'required',
            'description' => 'required',
            'logo'        => 'image|mimes:jpeg,bmp,png',
            'position'    => 'numeric',
            'interested_groups' => 'required'
        ];

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->passes()) {
            $stand->fill(Input::all());
            $stand->save();

            return Redirect::route('admin.careersfair.index')
                ->with('success', 'This careers fair company has been successfully updated');
        } else {
            return Redirect::route('admin.careersfair.edit', $stand->id)
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
        $stand = CareersFairStand::findOrFail($id);

        if ($stand->is_deletable) {
            $stand->delete();

            return Redirect::route('admin.careersfair.index')
                ->with('success', 'This careers fair company has been successfully deleted');
        } else {
            return Redirect::route('admin.careersfair.index')
                ->with('danger', 'This stand cannot be deleted');
        }
    }

}
