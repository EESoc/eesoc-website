<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class AddFamilyToUserTable
 *
 * Describes the database alterations to enable Mums And Dads families to be stored
 */
class AddFamilyToUserTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('users', function(Blueprint $table)
		{
			//This is for the first year
			$table->integer('child_of_family_id')->unsigned()->nullable()->default(null);

			//For the parents
			$table->integer('parent_of_family_id')->unsigned()->nullable()->default(null);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('users', function(Blueprint $table)
		{
			//
			$table->dropColumn(array('child_of_family_id', 'parent_of_family_id'));
		});
	}

}