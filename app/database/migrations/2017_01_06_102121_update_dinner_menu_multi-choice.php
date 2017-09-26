<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateDinnerMenuMultiChoice extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('dinner_group_members', function(Blueprint $table)
		{
			//Drop old vegetarian bool columns
			$table->dropColumn(array('vegetarian_starter', 'vegetarian_main'));

			//Add new menu choice
			$table->tinyInteger('choice_starter')->default(0);
			$table->tinyInteger('choice_main')->default(0);
			$table->tinyInteger('choice_dessert')->default(0);
			$table->text('special_req')->default("");

		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('dinner_group_members', function(Blueprint $table)
		{
			//Drop new menu choice cols
			$table->dropColumn(array('choice_starter', 'choice_main', 'choice_dessert', 'special_req'));


			//Readd old veg bool columns
			$table->boolean('vegetarian_starter');
			$table->boolean('vegetarian_main');
		});
	}

}