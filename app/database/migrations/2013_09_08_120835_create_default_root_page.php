<?php

use Illuminate\Database\Migrations\Migration;

class CreateDefaultRootPage extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Page::create([
			'name' => 'Home Page',
			'slug' => '',
			'type' => Page::TYPE_DATABASE,
			'content' => 'Hello world!',
		]);
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Page::where('slug', '=', '')->delete();
	}

}