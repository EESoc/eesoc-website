<?php

use Illuminate\Database\Migrations\Migration;

class CreateNewsletterEmailsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('newsletter_emails', function($table) {
			$table->increments('id');
			$table->integer('newsletter_id')->unsigned()->nullable();
			$table->foreign('newsletter_id')->references('id')->on('newsletters');
			$table->text('student_group_ids')->nullable();
			$table->string('subject');
			$table->text('body');
			$table->dateTime('send_at')->nullable();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('newsletter_emails');
	}

}