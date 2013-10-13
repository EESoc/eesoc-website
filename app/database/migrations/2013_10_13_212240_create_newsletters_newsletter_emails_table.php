<?php

use Illuminate\Database\Migrations\Migration;

class CreateNewslettersNewsletterEmailsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('newsletters_newsletter_emails', function($table) {
			$table->integer('newsletter_id')->unsigned();
			$table->foreign('newsletter_id')->references('id')->on('newsletters');
			$table->integer('newsletter_email_id')->unsigned();
			$table->foreign('newsletter_email_id')->references('id')->on('newsletter_emails');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('newsletters_newsletter_emails');
	}

}