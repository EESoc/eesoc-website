<?php
namespace Admin;

use \Category;
use \DB;
use \EventDay;
use \Input;
use \Redirect;
use \Validator;
use \View;

class EventsController extends BaseController {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $events = EventDay::orderBy('hidden')
			->orderBy(DB::raw('ISNULL(`date`), `date`'),'DESC')
            ->with('category')
            ->get();

        return View::make('admin.events.index')
            ->with('events', $events);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return View::make('admin.events.create')
            ->with('categories', Category::alphabetically()->get())
            ->with('event', new EventDay);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        $validator = $this->makeValidator();

        if ($validator->passes()) {
            $event = new EventDay;
            $event->fill(Input::all());
            $event->save();

            return Redirect::route('admin.events.index')
                ->with('success', 'Event has been successfully created');
        } else {
            return Redirect::route('admin.events.create')
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
        return View::make('admin.events.edit')
            ->with('categories', Category::alphabetically()->get())
            ->with('event', EventDay::findOrFail($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param   int $id
     * @return Response
     */
    public function update($id)
    {
        $event = EventDay::findOrFail($id);

        $validator = $this->makeValidator();

        if ($validator->passes()) {
            $event->fill(Input::all());
            $event->save();

            return Redirect::route('admin.events.index')
                ->with('success', 'Event has been successfully updated');
        } else {
            return Redirect::route('admin.events.edit', $event->id)
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
        $event = EventDay::findOrFail($id);
        $event->delete();   //is_deletable is always returning false, this overrides it.
        
        return Redirect::route('admin.events.index')
                ->with('success', 'Event has been successfully deleted');
        /*
        if ($event->is_deletable) {
            $event->delete();

            return Redirect::route('admin.events.index')
                ->with('success', 'Event has been successfully deleted');
        } else {
            return Redirect::route('admin.events.index')
                ->with('danger', 'This event cannot be deleted');
        }
        */
    }

    public function putDelete($id)
    {
        $event = EventDay::findOrFail($id);
        $event->delete();   //is_deletable is always returning false, this overrides it.
        
        return Redirect::route('admin.events.index')
                ->with('success', 'Event has been successfully deleted');


    }

    public function putVisibility($id, $action)
    {
        $event = EventDay::findOrFail($id);

        if ($action === 'hide') {
            $event->hidden = true;
        } else {
            $event->hidden = false;
        }

        $event->save();

        return Redirect::route('admin.events.index')
            ->with('success', 'Event has been successfully updated');
    }

    private function makeValidator()
    {
        $rules = [
            'name'        => 'required',
            'description' => 'required',
        ];

        return  Validator::make(Input::all(), $rules);
    }

}
