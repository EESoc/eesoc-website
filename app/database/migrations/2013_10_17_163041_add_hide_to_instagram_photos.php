<?php

use Illuminate\Database\Migrations\Migration;

class AddHideToInstagramPhotos extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('instagram_photos', function($table) {
            $table->boolean('hidden')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('instagram_photos', function($table) {
            $table->dropColumn('hidden');
        });
    }

}
