<?php

use Illuminate\Database\Migrations\Migration;

class CreateSponsorsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sponsors', function($table) {
            $table->increments('id');
            $table->string('name');
            $table->text('description');
            $table->string('logo');
            $table->integer('position')->default(-1);
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
        Schema::drop('sponsors');
    }

}
