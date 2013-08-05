<?php

class AdminCategoriesController extends AdminController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		return View::make('admin.categories.index')
			->with('categories', Category::orderBy('position')->get());
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return View::make('admin.categories.create')
			->with('category', new Category);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$inputs = array(
			'name' => Input::get('name'),
			'slug' => Input::get('slug'),
			'description' => Input::get('description'),
		);

		$rules = array(
			'name' => 'required',
			'slug' => 'unique:categories',
		);

		$validator = Validator::make($inputs, $rules);

		if ($validator->passes()) {
			$category = new Category;
			$category->fill($inputs);
			$category->save();

			return Redirect::route('admin.categories.index')->with('success', 'Category has been successfully created');
		} else {
			return Redirect::route('admin.categories.create')->withInput()->withErrors($validator);
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
		return View::make('admin.categories.edit')
			->with('category', Category::findOrFail($id));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$category = Category::findOrFail($id);

		$inputs = array(
			'name' => Input::get('name'),
			'slug' => Input::get('slug'),
			'description' => Input::get('description'),
		);

		$rules = array(
			'name' => 'required',
			'slug' => "unique:categories,slug,{$category->id}",
		);

		$validator = Validator::make($inputs, $rules);

		if ($validator->passes()) {
			$category->fill($inputs);
			$category->save();

			return Redirect::route('admin.categories.index')->with('success', 'Category has been successfully updated');
		} else {
			return Redirect::route('admin.categories.edit', $category->id)->withInput()->withErrors($validator);
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
		$category = Category::findOrFail($id);

		if ($category->is_deletable()) {
			$category->delete();

			return Redirect::route('admin.categories.index')->with('success', 'Category has been successfully deleted');
		} else {
			return Redirect::route('admin.categories.index')->with('danger', 'This category cannot be deleted');
		}
	}

}