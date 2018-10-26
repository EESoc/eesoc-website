<?php

use Illuminate\Database\Migrations\Migration;

class RenameCommitteeTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::rename('committee', 'committee_members');
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::rename('committee_members', 'committee');
	}

}