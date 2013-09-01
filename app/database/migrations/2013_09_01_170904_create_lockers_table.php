<?php

use Illuminate\Database\Migrations\Migration;

class CreateLockersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('locker_floors', function($table) {
			$table->increments('id');
			$table->string('name');
			$table->integer('floor');
		});

		Schema::create('locker_clusters', function($table) {
			$table->increments('id');
			$table->string('name');
			$table->integer('position');

			$table->integer('locker_floor_id')->unsigned();
			$table->foreign('locker_floor_id')->references('id')->on('locker_floors');
		});

		Schema::create('lockers', function($table) {
			$table->increments('id');
			$table->string('name');
			$table->text('description')->nullable()->default(null);
			$table->integer('row')->unsigned();
			$table->integer('column')->unsigned();

			$table->string('size');
			$table->string('status')->default('vacant');

			$table->integer('locker_cluster_id')->unsigned();
			$table->foreign('locker_cluster_id')->references('id')->on('locker_clusters');
			$table->unique(['locker_cluster_id', 'row', 'column']);

			$table->integer('owner_id')->unsigned()->nullable()->default(null);
			$table->foreign('owner_id')->references('id')->on('users');

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
		Schema::drop('lockers');
		Schema::drop('locker_clusters');
		Schema::drop('locker_floors');
	}

}