<?php
namespace Admin;

use \ChristmasDinnerSale;
use \Input;
use \Log;
use \Redirect;
use \User;
use \Validator;
use \View;

class ChristmasDinnerTicketsController extends BaseController {

	public function getIndex()
	{
		return View::make('admin.christmas_dinner_tickets.index')
			->with('sales', ChristmasDinnerSale::orderBy('created_at', 'desc')->get());
	}

	public function getNew()
	{
		return View::make('admin.christmas_dinner_tickets.new_order');
	}

	public function postConfirmation()
	{
		$validator = $this->makeValidator();

		if ($validator->passes()) {
			// Using IC Username
			$user = User::findOrCreateWithLDAP(Input::get('ic_username'));

			if ( ! $user) {
				// Cannot find user
				return Redirect::action('Admin\ChristmasDinnerTicketsController@getNew')
					->with('danger', 'Cannot find Imperial College User with this username. Please try again.');
			}

			return View::make('admin.christmas_dinner_tickets.confirmation')
				->with('user', $user)
				->with('quantity', (int) Input::get('quantity'));
		} else {
			return Redirect::action('Admin\ChristmasDinnerTicketsController@getNew')
				->withInput()
				->withErrors($validator);
		}
	}

	public function postPurchase()
	{
		$validator = $this->makeValidator();

		if ($validator->passes()) {
			$user = User::find(Input::get('user_id'));

			if ( ! $user) {
				// Cannot find user
				return Redirect::action('Admin\ChristmasDinnerTicketsController@getNew')
					->with('danger', 'Cannot find Imperial College User with this username. Please try again.');
			}

			$sale = new ChristmasDinnerSale;
			$sale->user()->associate($user);
			$sale->quantity = (int) Input::get('quantity');
			$sale->origin = 'admin';

			if ($sale->save()) {
				// Paper trail
				Log::info(sprintf('User `%s` purchased `%d` Christmas ticket(s)', $user->username, $sale->quantity), ['context' => 'christmas_dinner_tickets']);

				return Redirect::action('Admin\ChristmasDinnerTicketsController@getIndex')
					->with('success', 'Successfully purchased ticket(s)!');
			}

		} else {
			return Redirect::action('Admin\ChristmasDinnerTicketsController@getNew')
				->withInput()
				->withErrors($validator);
		}
	}

	private function makeValidator()
	{
		$rules = [
			'ic_username' => 'required_without:user_id',
			'user_id' => 'required_without:ic_username',
			'quantity' => 'required|numeric',
		];

		return Validator::make(Input::all(), $rules);
	}

}