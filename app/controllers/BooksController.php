<?php

class BooksController extends BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		return View::make('books.index')
			->with('books', Book::all());
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return View::make('books.create')
			->with('book', new Book);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$rules = [
			'name'      => 'required',
			'condition' => 'required',
			'price'     => 'required',
		];

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->passes()) {
			$book = new Book;
			$book->fill(Input::all());
			$book->save();

			return Redirect::route('dashboard.books.index')
				->with('success', 'Sponsor has been successfully created');
		} else {
			return Redirect::route('dashboard.books.create')
				->withInput()
				->withErrors($validator);
		}
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}