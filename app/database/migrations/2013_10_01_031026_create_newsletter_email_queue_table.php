<?php

use Illuminate\Database\Migrations\Migration;

class CreateNewsletterEmailQueueTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('newsletter_email_queue', function($table) {
			$table->increments('id');
			$table->integer('newsletter_email_id')->unsigned();
			$table->foreign('newsletter_email_id')->references('id')->on('newsletter_emails');
			$table->string('to');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('newsletter_email_queue');
	}

}
