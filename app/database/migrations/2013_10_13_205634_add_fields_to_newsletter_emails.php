<?php

use Illuminate\Database\Migrations\Migration;

class AddFieldsToNewsletterEmails extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('newsletter_emails', function($table) {
            $table->string('state')->default('draft');
            $table->string('preheader');
            $table->string('from_name');
            $table->string('from_email');
            $table->string('reply_to_email');
            $table->dropForeign('newsletter_emails_newsletter_id_foreign');
            $table->dropColumn('newsletter_id');
        });

        /* For reasons best known to SQLite and Doctrine, SQLite doesn't think the
         * field exists if this is used with the previous schemabuilder. */
        Schema::table('newsletter_emails', function($table) {
            $table->dropColumn('student_group_ids');
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
            $table->dropColumn('preheader');
            $table->dropColumn('from_name');
            $table->dropColumn('from_email');
            $table->dropColumn('reply_to_email');
            $table->integer('newsletter_id')->unsigned()->nullable();
            $table->foreign('newsletter_id')->references('id')->on('newsletters');
            $table->text('student_group_ids')->nullable();
        });
    }

}
