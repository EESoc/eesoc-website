<?php

use Illuminate\Database\Migrations\Migration;

class AddStudentGroupIdsToNewsletterEmailsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('newsletter_emails', function($table) {
			$table->text('student_group_ids')->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('newsletter_emails', function($table) {
			$table->dropColumn('student_group_ids');
		});
	}

}