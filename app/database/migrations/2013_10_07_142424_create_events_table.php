<?php

use Illuminate\Database\Migrations\Migration;

class CreateEventsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function($table) {
            $table->increments('id');
            $table->string('name');
            $table->date('date')->nullable()->default(null);
            $table->time('starts_at')->nullable()->default(null);
            $table->time('ends_at')->nullable()->default(null);
            $table->string('location');
            $table->text('description');
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
        Schema::drop('events');
    }

}
