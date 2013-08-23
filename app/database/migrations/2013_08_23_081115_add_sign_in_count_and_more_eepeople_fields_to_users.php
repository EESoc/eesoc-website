<?php

use Illuminate\Database\Migrations\Migration;

class AddSignInCountAndMoreEepeopleFieldsToUsers extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('users', function($table) {
			$table->dropColumn('eepeople_extras');
			$table->integer('sign_in_count')->unsigned()->default(0);
			$table->integer('eepeople_id')->unsigned()->nullable()->default(null);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('users', function($table) {
			$table->text('eepeople_extras');
			$table->dropColumn('sign_in_count');
			$table->dropColumn('eepeople_id');
		});
	}

}