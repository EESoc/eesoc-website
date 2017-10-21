<?php

use Illuminate\Database\Migrations\Migration;

class AddLockerAudit extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('lockers', function ($table) {
			$table->enum('audit', ['available', 'locked', 'broken'])->default("available");
			$table->dateTime('audit_date')->default("2016-10-05 16:00");
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//
		Schema::table('lockers', function ($table) {
			$table->dropColumn('audit');
			$table->dropColumn('audit_date');
		});
	}

}