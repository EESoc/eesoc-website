<?php

use Illuminate\Database\Migrations\Migration;

class LessChristmasColumns extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("dinner_group_members", function($table)
        {
            $table->renameColumn("christmas_dinner_group_id", "dinner_group_id");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table("dinner_group_members", function($table)
        {
            $table->renameColumn("dinner_group_id", "christmas_dinner_group_id");
        });
    }
}
