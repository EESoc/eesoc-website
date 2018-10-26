<?php

use Illuminate\Database\Migrations\Migration;

class CreateCommitteeTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('committee', function($table) {
			//foreign key does not work for MariaDB (maybe unless there is a unique id field)
            $table->engine = 'MyISAM';
			$table->increments('id');
			$table->string('name');
			$table->integer('user_id')->unsigned()->nullable()->default(null);
            $table->foreign('user_id')->references('id')->on('users');
			$table->string('role');
            $table->text('description');
            $table->string('logo');
            $table->integer('list_position')->default(-1);
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
