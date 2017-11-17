<?php

use Illuminate\Database\Migrations\Migration;

class CreateNewslettersUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('newsletters_users', function($table) {
            //foreign key does not work for MariaDB (maybe unless there is a unique id field)
            $table->engine = 'MyISAM';
            $table->integer('newsletter_id')->unsigned();
            $table->foreign('newsletter_id')->references('id')->on('newsletters');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('newsletters_users');
	}

}