<?php

use Illuminate\Database\Migrations\Migration;

class AddLastUpdatedByToEmails extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('newsletter_emails', function($table) {
            $table->string('last_updated_by');
            $table->dateTime('last_updated_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('newsletter_emails', function($table) {
            $table->dropColumn('last_updated_by');
            $table->dropColumn('last_updated_at');
        });
    }

}
