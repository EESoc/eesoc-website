<?php

use Illuminate\Database\Migrations\Migration;

class AddFieldsToNewsletterEmailQueueTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('newsletter_email_queue', function($table) {
            $table->boolean('sent')->default(false);
            $table->dropColumn('to');
            $table->string('to_email');
            $table->string('tracker_token');
            $table->integer('views')->unsigned()->default(0);
            $table->timestamps();
        });

        try
        {
            Schema::table('newsletter_email_queue', function($table) {
                $table->unique('tracker_token');
            });
        }
        catch (\Exception $e)
        {
            echo "Couldn't create UNIQUE constraint on newsletter_email_queue."
                ."tracker_token. This is to be expected if you are using SQLite; "
                ."modification of uniqueness of an extant column is not permitted."
                .PHP_EOL;
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('newsletter_email_queue', function($table) {
            $table->dropColumn('sent');
            $table->dropColumn('to_email');
            $table->string('to');
            $table->dropColumn('tracker_token');
            $table->dropColumn('views');
            $table->dropColumn('created_at');
            $table->dropColumn('updated_at');
        });
    }

}
