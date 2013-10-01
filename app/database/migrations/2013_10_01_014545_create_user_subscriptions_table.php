<?php

use Illuminate\Database\Migrations\Migration;

class CreateUserSubscriptionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('user_subscriptions', function($table) {
			$table->increments('id');
			$table->integer('user_id')->unsigned()->nullable();
			$table->foreign('user_id')->references('id')->on('users');
			$table->integer('newsletter_id')->unsigned();
			$table->foreign('newsletter_id')->references('id')->on('newsletters');
			$table->string('email')->nullable();
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
		Schema::drop('user_subscriptions');
	}

}