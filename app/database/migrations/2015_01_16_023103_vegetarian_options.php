<?php

use Illuminate\Database\Migrations\Migration;

class VegetarianOptions extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::table('dinner_group_members', function($table)
        {
            $table->boolean('vegetarian_starter')->default(FALSE);
            $table->boolean('vegetarian_main')->default(FALSE);
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::table('dinner_group_members', function($table)
        {
            $table->dropColumn('vegetarian_starter');
            $table->dropColumn('vegetarian_main');
        });
	}

}
