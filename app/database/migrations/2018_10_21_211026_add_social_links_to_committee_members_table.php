<?php

use Illuminate\Database\Migrations\Migration;

class AddSocialLinksToCommitteeMembersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('committee_members', function ($table) {
			$table->string('facebookURL')->nullable()->default(null)->after('logo');
			$table->string('githubURL')->nullable()->default(null)->after('facebookURL');
			$table->string('email')->nullable()->default(null)->after('githubURL');
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
			$table->dropColumn('facebookURL');
			$table->dropColumn('githubURL');
			$table->dropColumn('email');
		});
	}

}