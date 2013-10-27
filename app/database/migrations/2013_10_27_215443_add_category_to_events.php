<?php

use Illuminate\Database\Migrations\Migration;

class AddCategoryToEvents extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('events', function($table) {
			$table->integer('category_id')->unsigned()->nullable()->default(null);
			$table->foreign('category_id')->references('id')->on('categories');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('events', function($table) {
			$table->dropForeign('events_category_id_foreign');
			$table->dropColumn('category_id');
		});
	}

}