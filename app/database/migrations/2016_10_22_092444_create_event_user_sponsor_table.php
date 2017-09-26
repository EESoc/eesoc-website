<?php

use Illuminate\Database\Migrations\Migration;

class CreateEventUserSponsorTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('event_user', function($table)
{
			$table->increments('id');
			$table->integer('event_id');
			$table->integer('user_id');
			$table->timestamps();
		});
		
		Schema::create('event_sponsor', function($table)
{
			$table->increments('id');
			$table->integer('event_id');
			$table->integer('sponsor_id');
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
		Schema::drop('event_user');
		Schema::drop('event_sponsor');
	}

}