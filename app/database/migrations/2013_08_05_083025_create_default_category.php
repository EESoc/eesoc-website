<?php

use Illuminate\Database\Migrations\Migration;

class CreateDefaultCategory extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		$category = new Category;
		$category->name = 'Uncategorised';
		$category->slug = 'uncategorised';
		$category->save();
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		$category = Category::where('name', '=', 'Uncategorised')->first();

		if ($category) {
			$category->delete();
		}
	}

}