<?php

use Illuminate\Database\Migrations\Migration;

class AddNotifiedToChristmasDinnerSales extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('christmas_dinner_sales', function($table) {
            $table->boolean('notified')->default(false);
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
            $table->dropColumn('notified');
        });
    }

}
