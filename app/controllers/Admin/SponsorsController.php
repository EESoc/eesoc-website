<?php
namespace Admin;

use \Sponsor;
use \Input;
use \Redirect;
use \Validator;
use \View;

class SponsorsController extends BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		return View::make('admin.sponsors.index')
			->with('sponsors', Sponsor::all());
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return View::make('admin.sponsors.create')
			->with('sponsor', new Sponsor);
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
		];

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->passes()) {
			$sponsor = new Sponsor;
			$sponsor->fill(Input::all());
			$sponsor->save();

			return Redirect::route('admin.sponsors.index')
				->with('success', 'Sponsor has been successfully created');
		} else {
			return Redirect::route('admin.sponsors.create')
				->withInput()
				->withErrors($validator);
		}
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param	int	$id
	 * @return Response
	 */
	public function edit($id)
	{
		return View::make('admin.sponsors.edit')
			->with('sponsor', Sponsor::findOrFail($id));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param	int	$id
	 * @return Response
	 */
	public function update($id)
	{
		$sponsor = Sponsor::findOrFail($id);

		$rules = [
			'name'        => 'required',
			'description' => 'required',
			'logo'        => 'image|mimes:jpeg,bmp,png',
			'position'    => 'numeric',
		];

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->passes()) {
			$sponsor->fill(Input::all());
			$sponsor->save();

			return Redirect::route('admin.sponsors.index')
				->with('success', 'Sponsor has been successfully updated');
		} else {
			return Redirect::route('admin.sponsors.edit', $sponsor->id)
				->withInput()
				->withErrors($validator);
		}
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param	int	$id
	 * @return Response
	 */
	public function destroy($id)
	{
		$sponsor = Sponsor::findOrFail($id);

		if ($sponsor->is_deletable) {
			$sponsor->delete();

			return Redirect::route('admin.sponsors.index')
				->with('success', 'Sponsor has been successfully deleted');
		} else {
			return Redirect::route('admin.sponsors.index')
				->with('danger', 'This sponsor cannot be deleted');
		}
	}

}