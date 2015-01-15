<?php

use Illuminate\Database\Migrations\Migration;

class MaxDinnerTableSize extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dinner_groups', function($table)
        {
            $table->integer('max_size')->default(10)->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dinner_groups', function ($table)
        {
            $table->dropColumn('max_size');
        });
    }
}
