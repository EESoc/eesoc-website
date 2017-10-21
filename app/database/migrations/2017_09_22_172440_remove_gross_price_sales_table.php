<?php

use Illuminate\Database\Migrations\Migration;

class RemoveGrossPriceSalesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::table('sales', function ($table) {
			$table->dropColumn('gross_price_in_pence');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('sales', function ($table) {
			$table->integer('gross_price_in_pence')->unsigned();
		});
	}

}