<?php

use Illuminate\Database\Migrations\Migration;

class AddTargetStudentGroupsToBooks extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('books', function($table) {
            $table->string('target_student_groups');
        });

        Schema::drop('books_student_groups');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('books', function($table) {
            $table->dropColumn('target_student_groups');
        });

        Schema::create('books_student_groups', function($table) {
            $table->integer('book_id')->unsigned();
            $table->foreign('book_id')->references('id')->on('books');
            $table->integer('student_group_id')->unsigned();
            $table->foreign('student_group_id')->references('id')->on('student_groups');
        });
    }

}
