<?php

use Illuminate\Database\Migrations\Migration;

class AddProductIdToSales extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('sales', function($table) {
			$table->integer('product_id')->unsigned();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('sales', function($table) {
			$table->dropColumn('product_id');
		});
	}

}