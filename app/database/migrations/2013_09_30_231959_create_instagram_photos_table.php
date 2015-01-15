<?php

use Illuminate\Database\Migrations\Migration;

class CreateInstagramPhotosTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('instagram_photos', function($table) {
            $table->string('id')->primary();
            $table->integer('user_id')->unsigned()->nullable();
            $table->foreign('user_id')->references('id')->on('users');
            $table->text('tags');
            $table->decimal('latitude', 18, 12)->nullable();
            $table->decimal('longitude', 18, 12)->nullable();
            $table->integer('captured_time')->unsigned();
            $table->string('link');
            $table->string('image_low_resolution_url');
            $table->string('image_thumbnail_url');
            $table->string('image_standard_resolution_url');
            $table->string('instagram_username');
            $table->string('instagram_user_id');
            $table->string('type');
            $table->boolean('is_approved')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('instagram_photos');
    }

}
