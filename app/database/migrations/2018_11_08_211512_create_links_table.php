<?php

use Illuminate\Database\Migrations\Migration;

class CreateLinksTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('links', function($table) {
			//foreign key does not work for MariaDB
            $table->engine = 'MyISAM';
			$table->increments('id');
			$table->string('slug')->unique();	//short url
			$table->text('full_url'); //problem with uniqueness and setting custom size, its old mysql issue, leave as text for now
			$table->date('expiry_date')->nullable()->default(null); //null implies does not expire
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
		Schema::drop('links');
	}

}