<?php

use Illuminate\Database\Migrations\Migration;

class AddSaleIdToChristmasDinnerSales extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('christmas_dinner_sales', function($table) {
            $table->integer('sale_id')->unsigned()->nullable()->default(null);
            $table->foreign('sale_id')->references('id')->on('sales');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('christmas_dinner_sales', function($table) {
            $table->dropForeign('christmas_dinner_sales_sale_id_foreign');
            $table->dropColumn('sale_id');
        });
    }

}
