<?php

use Illuminate\Database\Migrations\Migration;

class AddFieldsToNewsletterEmailQueueTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('newsletter_email_queue', function($table) {
			$table->increments('id')->before('newsletter_email_id');
			$table->boolean('sent')->default(false);
			$table->dropColumn('to');
			$table->string('to_email');
			$table->string('tracker_token')->unique();
			$table->integer('views')->unsigned()->default(0);
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
		Schema::table('newsletter_email_queue', function($table) {
			$table->dropColumn('sent');
			$table->dropColumn('to_email');
			$table->string('to');
			$table->dropColumn('tracker_token');
			$table->dropColumn('views');
			$table->dropColumn('created_at');
			$table->dropColumn('updated_at');
		});
	}

}