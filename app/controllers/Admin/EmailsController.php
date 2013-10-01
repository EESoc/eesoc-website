<?php
namespace Admin;

use \NewsletterEmail;
use \NewsletterEmailQueue;
use \Input;
use \Redirect;
use \Validator;
use \View;

class EmailsController extends BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		return View::make('admin.emails.index')
			->with('emails', NewsletterEmail::all());
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return View::make('admin.emails.create')
			->with('email', new NewsletterEmail);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$inputs = array(
			'subject'       => Input::get('subject'),
			'body'          => Input::get('body'),
			'newsletter_id' => Input::get('newsletter_id'),
			'queue_send'    => Input::get('queue_send'),
		);

		$rules = array();
		if ($inputs['queue_send']) {
			$rules = array(
				'subject'       => 'required',
				'body'          => 'required',
				'newsletter_id' => 'required',
			);
		}

		$validator = Validator::make($inputs, $rules);

		if ($validator->passes()) {
			$email = new NewsletterEmail;
			$email->fill($inputs);
			$email->save();

			if ($inputs['queue_send']) {
				$this->queueEmail($email);
			}

			return Redirect::route('admin.emails.index')->with('success', 'Email has been successfully created');
		} else {
			return Redirect::route('admin.emails.create')->withInput()->withErrors($validator);
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
		return View::make('admin.emails.edit')
			->with('email', NewsletterEmail::findOrFail($id));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param	int	$id
	 * @return Response
	 */
	public function update($id)
	{
		$email = NewsletterEmail::findOrFail($id);

		$inputs = array(
			'subject'       => Input::get('subject'),
			'body'          => Input::get('body'),
			'newsletter_id' => Input::get('newsletter_id'),
			'queue_send'    => Input::get('queue_send'),
		);

		$rules = array();
		if ($inputs['queue_send']) {
			$rules = array(
				'subject'       => 'required',
				'body'          => 'required',
				'newsletter_id' => 'required',
			);
		}

		$validator = Validator::make($inputs, $rules);

		if ($validator->passes()) {
			$email->fill($inputs);
			$email->save();

			if ($inputs['queue_send']) {
				$this->queueEmail($email);
			}

			return Redirect::route('admin.emails.index')->with('success', 'Email has been successfully updated');
		} else {
			return Redirect::route('admin.emails.edit', $email->id)->withInput()->withErrors($validator);
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
		$NewsletterEmail = NewsletterEmail::findOrFail($id);

		if ($NewsletterEmail->is_deletable) {
			$NewsletterEmail->delete();

			return Redirect::route('admin.emails.index')->with('success', 'NewsletterEmail has been successfully deleted');
		} else {
			return Redirect::route('admin.emails.index')->with('danger', 'This NewsletterEmail cannot be deleted');
		}
	}

	/**
	 * Queue a newsletter email
	 * @param  NewsletterEmail $email
	 */
	private function queueEmail(NewsletterEmail $email)
	{
		if ($email->newsletter) {
			foreach ($email->newsletter->users as $user) {
				$queue = new NewsletterEmailQueue;
				$queue->newsletterEmail()->associate($email);
				$queue->to = $user->email;
				$queue->save();
			}
		} else if ($email->group_ids) {

		}
	}

}