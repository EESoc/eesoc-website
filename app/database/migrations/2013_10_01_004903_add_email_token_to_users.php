<?php

use Illuminate\Database\Migrations\Migration;

class AddEmailTokenToUsers extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::transaction(function() {
            Schema::table('users', function($table) {
                $table->string('email_token')->unique()->nullable();
            });

            foreach (User::all() as $user) {
                $user->touch();
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function($table) {
            $table->dropColumn('email_token');
        });
    }

}
