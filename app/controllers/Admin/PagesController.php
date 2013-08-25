<?php
namespace Admin;

use Page;
use Input;
use Redirect;
use Validator;
use View;

class PagesController extends BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		return View::make('admin.pages.index')
			->with('pages', Page::all());
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return View::make('admin.pages.create')
			->with('page', new Page);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$rules = array(
			'name'    => 'required',
			'slug'    => 'unique:pages',
			'content' => 'required',
		);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->passes()) {
			$page = new Page;
			$page->fill(Input::all());
			$page->save();

			return Redirect::route('admin.pages.index')
				->with('success', 'Page has been successfully created');
		} else {
			return Redirect::route('admin.pages.create')
				->withInput()
				->withErrors($validator);
		}
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		return View::make('admin.pages.edit')
			->with('page', Page::findOrFail($id));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$page = Page::findOrFail($id);

		$rules = array(
			'name'    => 'required',
			'slug'    => "unique:pages,slug,{$page->id}",
			'content' => 'required',
		);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->passes()) {
			$page->fill(Input::all());
			$page->save();

			return Redirect::route('admin.pages.index')
				->with('success', 'Page has been successfully updated');
		} else {
			return Redirect::route('admin.pages.edit', $page->id)
				->withInput()
				->withErrors($validator);
		}
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$page = Page::findOrFail($id);

		if ($page->is_deletable) {
			$page->delete();

			return Redirect::route('admin.pages.index')
				->with('success', 'Page has been successfully deleted');
		} else {
			return Redirect::route('admin.pages.index')
				->with('danger', 'This page cannot be deleted');
		}
	}

}