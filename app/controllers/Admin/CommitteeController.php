<?php
namespace Admin;

use \CommitteeMember;
use \Input;
use \Redirect;
use \Validator;
use \View;

class CommitteeController extends BaseController {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return View::make('admin.committee.index')
            ->with('members', CommitteeMember::all());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return View::make('admin.committee.create')
            ->with('member', new CommitteeMember);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        $rules = [
            'name'              => 'required',
            'role'              => 'required',
            'short_description' => 'required',
            'logo'              => 'required|image|mimes:jpeg',
            'list_position'     => 'numeric',
            'githubURL'         => 'url',
            'facebookURL'       => 'url',
            'email'             => 'required|email',
        ];

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->passes()) {
            $member = new CommitteeMember;
            $member->fill(Input::all());
            $member->save();

            return Redirect::route('admin.committee.index')
                ->with('success', 'Committee Member has been successfully created');
        } else {
            return Redirect::route('admin.committee.create')
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
        return View::make('admin.committee.edit')
            ->with('member', CommitteeMember::findOrFail($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param   int $id
     * @return Response
     */
    public function update($id)
    {
        $member = CommitteeMember::findOrFail($id);

        $rules = [
            'name'              => 'required',
            'role'              => 'required',
            'short_description' => 'required',
            'logo'              => 'image|mimes:jpeg',
            'list_position'     => 'numeric',
            'githubURL'         => 'url',
            'facebookURL'       => 'url',
            'email'             => 'required|email',
        ];

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->passes()) {
            //@Haaris: also edit 'fillable' field in model if using newer fields!
            $member->fill(Input::all());
            $member->save();

            return Redirect::route('admin.committee.index')
                ->with('success', 'Committee member has been successfully updated');
        } else {
            return Redirect::route('admin.committee.edit', $member->id)
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
        $sponsor = CommitteeMember::findOrFail($id);
        $sponsor->delete();
        
        return Redirect::route('admin.committee.index')
            ->with('success', 'Committee Member has been successfully deleted');

        // same deletable issues
        /*
        if ($sponsor->is_deletable) {
            $sponsor->delete();

            return Redirect::route('admin.sponsors.index')
                ->with('success', 'Sponsor has been successfully deleted');
        } else {
            return Redirect::route('admin.sponsors.index')
                ->with('danger', 'This sponsor cannot be deleted');
        }
        */
    }

}
