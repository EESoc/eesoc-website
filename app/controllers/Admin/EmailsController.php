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
			->with('emails', NewsletterEmail::orderBy('created_at', 'desc')->get());
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
			$email->refreshLastUpdatedBy(Auth::user());

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
		$validator = $this->makeValidator();

		if ($validator->passes()) {
			$email = new NewsletterEmail;
			$email->fill(Input::all());
			$email->save();
			$email->newsletters()->sync((array) Input::get('newsletter_ids'));

			if ($response = $this->afterSaveResponseForEmail($email)) {
				return $response;
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

		$validator = $this->makeValidator();

		if ($validator->passes()) {
			if ($email->can_save) {
				$email->fill(Input::all());
				$email->save();
				$email->newsletters()->sync((array) Input::get('newsletter_ids'));

				if ($response = $this->afterSaveResponseForEmail($email)) {
					return $response;
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
        $errors      = [];

		if ($email->is_sending) {
            $sent_emails = $email->sendBatch(10, $errors);
		}

		$panel = View::make('admin.emails.send_panel_body')
			->with('email', $email)
			->render();

        $return_data = [
			'sending'     => $email->is_sending,
			'panel'       => $panel,
			'sent_emails' => $sent_emails,
		];

        if ($errors)
        {
            $error_string = implode(PHP_EOL, array_map(function($value)
            {
                return $value->getMessage();
            }, $errors));

            $return_data['error'] = $error_string;
        }

        return Response::json($return_data);
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

	/**
	 * Return validator class with rules based on requested action.
	 * @return Validator
	 */
	private function makeValidator()
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

		return Validator::make(Input::all(), $rules);
	}

	/**
	 * Decide what do do based on requested action.
	 * @param  NewsletterEmail $email
	 * @return Response
	 */
	private function afterSaveResponseForEmail(NewsletterEmail $email)
	{
		if (Input::get('action') === 'send') {
			$email->buildEmailQueue();
			$email->state = 'sending';
			$email->save();

			return Redirect::route('admin.emails.show', $email->id)
				->with('success', 'Email has been successfully updated');
		}

		if (Input::get('action') === 'send_test') {
			$email->sendTestToUser(Auth::user());
			return Redirect::route('admin.emails.edit', $email->id)
				->with('success', 'Test Email has been successfully sent');
		}
	}
}
