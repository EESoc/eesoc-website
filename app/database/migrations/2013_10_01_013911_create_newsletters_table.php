<?php

use Illuminate\Database\Migrations\Migration;

class CreateNewslettersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('newsletters', function($table) {
			$table->increments('id');
			$table->string('name');
			$table->boolean('is_active')->default(true);
			$table->boolean('is_for_members')->default(true);
			$table->boolean('is_for_non_members')->default(true);
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
		Schema::drop('newsletters');
	}

}