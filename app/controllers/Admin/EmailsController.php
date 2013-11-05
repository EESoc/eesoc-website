<?php
namespace Admin;

use \Auth;
use \Input;
use \Newsletter;
use \NewsletterEmail;
use \NewsletterEmailQueue;
use \Redirect;
use \Response;
use \StudentGroup;
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
			->with('email', new NewsletterEmail)
			->with('newsletters', Newsletter::all());
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param	int	$id
	 * @return Response
	 */
	public function edit($id)
	{
		$email = NewsletterEmail::findOrFail($id);

		if ($email->can_save) {
			return View::make('admin.emails.edit')
				->with('email', $email)
				->with('newsletters', Newsletter::all());
		} else {
			return Redirect::route('admin.emails.show', $email->id);
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
		$email = NewsletterEmail::findOrFail($id);

		if ($email->can_save) {
			return Redirect::route('admin.emails.edit', $email->id);
		} else {
			return View::make('admin.emails.show')
				->with('email', $email);
		}
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		if (Input::get('action') === 'send' || Input::get('action') === 'send_test') {
			$rules = [
				'subject'    => 'required',
				'preheader'  => 'required',
				'from_name'  => 'required',
				'from_email' => 'required',
				'body'       => 'required',
			];
		} else {
			$rules = [];
		}

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->passes()) {
			if ($email->can_save) {
				$email->fill(Input::all());
				$email->save();
				$email->newsletters()->sync((array) Input::get('newsletter_ids'));

				if (Input::get('action') === 'send') {
					$email->buildEmailQueue();
					$email->state = 'sending';
					$email->save();

					return Redirect::route('admin.emails.show', $email->id)
						->with('success', 'Email has been successfully updated');
				}

				if (Input::get('action') === 'send_test') {
					$email->sendTestToUser(Auth::user());
					return Redirect::route('admin.emails.show', $email->id)
						->with('success', 'Test Email has been successfully sent');
				}
			}

			return Redirect::route('admin.emails.edit', $email->id)
				->with('success', 'Email has been successfully created');
		} else {
			return Redirect::route('admin.emails.create')
				->withInput()
				->withErrors($validator);
		}
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

		if (Input::get('action') === 'send' || Input::get('action') === 'send_test') {
			$rules = [
				'subject'    => 'required',
				'preheader'  => 'required',
				'from_name'  => 'required',
				'from_email' => 'required',
				'body'       => 'required',
			];
		} else {
			$rules = [];
		}

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->passes()) {
			if ($email->can_save) {
				$email->fill(Input::all());
				$email->save();
				$email->newsletters()->sync((array) Input::get('newsletter_ids'));

				if (Input::get('action') === 'send') {
					$email->buildEmailQueue();
					$email->state = 'sending';
					$email->save();

					return Redirect::route('admin.emails.show', $email->id)
						->with('success', 'Email has been successfully updated');
				}

				if (Input::get('action') === 'send_test') {
					$email->sendTestToUser(Auth::user());
					return Redirect::route('admin.emails.show', $email->id)
						->with('success', 'Test Email has been successfully sent');
				}
			}

			return Redirect::route('admin.emails.edit', $email->id)
				->with('success', 'Email has been successfully updated');
		} else {
			return Redirect::route('admin.emails.edit', $email->id)
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
		$NewsletterEmail = NewsletterEmail::findOrFail($id);

		if ($NewsletterEmail->is_deletable) {
			$NewsletterEmail->delete();

			return Redirect::route('admin.emails.index')->with('success', 'NewsletterEmail has been successfully deleted');
		} else {
			return Redirect::route('admin.emails.index')->with('danger', 'This NewsletterEmail cannot be deleted');
		}
	}

	public function getPreview($id)
	{
		$email = NewsletterEmail::findOrFail($id);

		$html = View::make('email_layouts.basic')
			->with('body', $email->body)
			->render();

		return (new \TijsVerkoyen\CssToInlineStyles\CssToInlineStyles($html, file_get_contents(base_path() . '/public/assets/css/email.css')))
			->convert();
	}

	public function postSendBatch($id)
	{
		$email = NewsletterEmail::findOrFail($id);

		$sent_emails = [];

		if ($email->is_sending) {
			try {
				$sent_emails = $email->sendBatch();
			} catch (\Swift_TransportException $e) {
				return Response::json(['error' => $e->getMessage()]);
			}
		}

		$panel = View::make('admin.emails.send_panel_body')
			->with('email', $email)
			->render();

		return Response::json([
			'sending' => $email->is_sending,
			'panel' => $panel,
			'sent_emails' => $sent_emails,
		]);
	}

	public function putPause($id)
	{
		$email = NewsletterEmail::findOrFail($id);

		if ($email->can_pause) {
			$email->state = 'draft';
			$email->save();

			return Redirect::route('admin.emails.edit', $email->id)
				->with('success', 'Email sending paused');
		} else {

			return Redirect::route('admin.emails.show', $email->id)
				->with('success', 'You cannot pause this email');
		}
	}

	public function getPreviewTemplate($id)
	{
		$email = NewsletterEmail::findOrFail($id);

		return View::make('email_layouts.newsletter')
			->with('body', null);
	}

}