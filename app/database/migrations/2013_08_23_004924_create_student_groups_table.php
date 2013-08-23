<?php

use Illuminate\Database\Migrations\Migration;

class CreateStudentGroupsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('student_groups', function($table) {
			$table->increments('id');
			$table->string('group_id')->nullable()->default(null);
			$table->string('name');
			$table->string('parent_id')->nullable()->default(null);
			$table->boolean('is_official')->default(false);
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
		Schema::drop('student_groups');
	}

}