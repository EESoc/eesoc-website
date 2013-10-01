<?php

class NewslettersController extends BaseController {

	public function getSignup($id)
	{
		$newsletter = Newsletter::findOrFail($id);

		return View::make('newsletters.signup_form')
			->with('newsletter', $newsletter);
	}

	public function postSignup($id)
	{
		$newsletter = Newsletter::findOrFail($id);

		$inputs = array(
			'ic_username' => Input::get('ic_username'),
			'email'       => Input::get('email'),
		);

		$rules = array(
			'ic_username' => 'required_without:email',
			'email'       => 'required_without:ic_username|email',
		);

		$validator = Validator::make($inputs, $rules);

		if ($validator->passes()) {
			$subscription = new UserSubscription;
			$subscription->newsletter()->associate($newsletter);

			if ($inputs['ic_username']) {
				// Using IC Username
				$user = User::findOrCreateWithLDAP($inputs['ic_username']);

				if ( ! $user) {
					// Cannot find user
					return Redirect::action('NewslettersController@getSignup', $newsletter->id)
						->with('danger', 'Cannot find Imperial College User with this username. Please try again.');
				}

				$subscription->user()->associate($user);
			} else {
				// Using email
				$subscription->email = $inputs['email'];
			}

			// Create subscription
			$subscription->save();

			// Log in paper trail
			if ($inputs['ic_username']) {
				Log::info(sprintf('User with email `%s` subscribed to newsletter `%s`', $subscription->user->username, $newsletter->name), ['context' => 'newsletters']);
			} else {
				Log::info(sprintf('User with IC username `%s` subscribed to newsletter `%s`', $subscription->email, $newsletter->name), ['context' => 'newsletters']);
			}

			return Redirect::action('NewslettersController@getSignup', $newsletter->id)
				->with('success', 'Successfully signed up to newsletter!');
		} else {
			return Redirect::action('NewslettersController@getSignup', $newsletter->id)
				->withInput()
				->withErrors($validator);
		}
	}

}