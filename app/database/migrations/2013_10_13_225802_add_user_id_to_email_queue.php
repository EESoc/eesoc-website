<?php

use Illuminate\Database\Migrations\Migration;

class AddUserIdToEmailQueue extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('newsletter_email_queue', function($table) {
			$table->integer('user_id')->unsigned()->nullable()->default(null);
			$table->foreign('user_id')->references('id')->on('users');
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
			$table->dropForeign('newsletter_email_queue_user_id_foreign');
			$table->dropColumn('user_id');
		});
	}

}