<?php

use Illuminate\Database\Migrations\Migration;

class AddDinnerMemberPk extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::table('dinner_group_members', function($table)
        {
            $table->increments('id');
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
            $table->dropColumn('id');
        });
	}

}
