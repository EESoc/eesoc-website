<?php

use Illuminate\Database\Migrations\Migration;

class CreateNewslettersStudentGroupsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('newsletters_student_groups', function($table) {
            $table->integer('newsletter_id')->unsigned();
            $table->foreign('newsletter_id')->references('id')->on('newsletters');
            $table->integer('student_group_id')->unsigned();
            $table->foreign('student_group_id')->references('id')->on('student_groups');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('newsletters_student_groups');
    }

}
