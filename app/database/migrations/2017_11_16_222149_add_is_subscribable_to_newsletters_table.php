<?php

use Illuminate\Database\Migrations\Migration;

class AddIsSubscribableToNewslettersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('newsletters', function ($table) {
            $table->tinyInteger('is_subscribable')->default(0)->after('is_for_non_members');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('newsletters', function ($table) {
			$table->dropColumn('is_subscribable');
		});
	}

}