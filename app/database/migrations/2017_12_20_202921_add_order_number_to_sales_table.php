<?php

use Illuminate\Database\Migrations\Migration;

class AddOrderNumberToSalesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        //This migration creates an order_number field with default 0
        //On original run data needs to be copied from id field to order_number manually
        //When starting from scratch this will be not required so ignored in migration
        //The purpose of this field is that two unique products can have the same order_number
        //Causing a race condition which over-writes data on sync
        //In future both order-number and product id will be checked
        //The original id field will remain as auto-increment and thus remain unique

        Schema::table('sales', function ($table) {
            $table->integer('order_number')->unsigned()->index()->default(0)->after('id');
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
			$table->dropColumn('order_number');
		});
	}

}