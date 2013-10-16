<?php

class BooksController extends BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$my_books = Book::ownedBy(Auth::user())
			->get();
		$other_books = Book::notOwnedBy(Auth::user())
			->with('user')
			->ordered()
			->get();
		
		return View::make('books.index')
			->with('my_books', $my_books)
			->with('other_books', $other_books);
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
			'name'                 => 'required',
			'condition'            => 'required',
			'price'                => 'required',
			'contact_instructions' => 'required',
		];

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->passes()) {
			$book = new Book;
			$book->user()->associate(Auth::user());
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
		$book = Book::ownedBy(Auth::user())
			->where('id', '=', $id)
			->firstOrFail();

		return View::make('books.edit')
			->with('book', $book);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$book = Book::ownedBy(Auth::user())
			->where('id', '=', $id)
			->firstOrFail();

		$rules = [
			'name'                 => 'required',
			'condition'            => 'required',
			'price'                => 'required',
			'contact_instructions' => 'required',
		];

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->passes()) {
			$book->fill(Input::all());
			$book->save();

			return Redirect::route('dashboard.books.index')
				->with('success', 'Book has been successfully updated');
		} else {
			return Redirect::route('dashboard.books.edit', $book->id)
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
		$book = Book::ownedBy(Auth::user())
			->where('id', '=', $id)
			->firstOrFail();
		$book->delete();

		return Redirect::route('dashboard.books.index')
			->with('success', 'Book has been successfully deleted');
	}

}