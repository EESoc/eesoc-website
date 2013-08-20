<?php

use Illuminate\Database\Migrations\Migration;

class AddCidAndMemberToUsers extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('users', function($table) {
			$table->string('cid')->nullable()->default(null);
			$table->boolean('is_member')->default(false);
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
			$table->dropColumn('cid');
			$table->dropColumn('is_member');
		});
	}

}