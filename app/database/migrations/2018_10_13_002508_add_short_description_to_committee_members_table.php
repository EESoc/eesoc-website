<?php

use Illuminate\Database\Migrations\Migration;

class AddShortDescriptionToCommitteeMembersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('committee_members', function ($table) {
            $table->string('short_description')->default(0)->after('role');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('committee_members', function ($table) {
			$table->dropColumn('short_description');
		});
	}

}