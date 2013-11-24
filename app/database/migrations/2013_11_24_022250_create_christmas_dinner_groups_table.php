<?php

use Illuminate\Database\Migrations\Migration;

class CreateChristmasDinnerGroupsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('christmas_dinner_groups', function($table) {
			$table->increments('id');
			$table->integer('owner_id')->unsigned();
			$table->foreign('owner_id')->references('id')->on('users');
			$table->boolean('is_full')->default(false);
			$table->timestamps();
		});

		Schema::create('christmas_dinner_group_members', function($table) {
			$table->integer('christmas_dinner_group_id')->unsigned();
			$table->foreign('christmas_dinner_group_id')->references('id')->on('christmas_dinner_groups');
			$table->integer('user_id')->unsigned();
			$table->foreign('user_id')->references('id')->on('users');
			$table->integer('added_by_user_id')->unsigned()->nullable()->default(null);
			$table->foreign('added_by_user_id')->references('id')->on('users');
			$table->integer('ticket_purchaser_id')->unsigned();
			$table->foreign('ticket_purchaser_id')->references('id')->on('christmas_dinner_sales');
			$table->boolean('is_owner')->default(false);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('christmas_dinner_group_members');
		Schema::drop('christmas_dinner_groups');
	}

}