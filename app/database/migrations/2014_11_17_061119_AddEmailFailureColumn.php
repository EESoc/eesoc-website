<?php

use Illuminate\Database\Migrations\Migration;

class AddEmailFailureColumn extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('newsletter_email_queue', function($table)
        {
            $table->boolean('failed')->default(FALSE);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $table->dropColumn('failed');
    }
}
