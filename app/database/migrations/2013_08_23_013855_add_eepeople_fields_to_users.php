<?php

use Illuminate\Database\Migrations\Migration;

class AddEepeopleFieldsToUsers extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function($table) {
            $table->string('tutor_name')->nullable()->default(null);
            $table->integer('student_group_id')->unsigned()->nullable()->default(null);
            $table->foreign('student_group_id')->references('id')->on('student_groups');
            $table->string('image_content_type')->nullable()->default(null);
            $table->binary('image_blob')->nullable()->default(null);
            $table->text('eepeople_extras');
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
            $table->dropColumn('tutor_name');
            $table->dropForeign('users_student_group_id_foreign');
            $table->dropColumn('student_group_id');
            $table->dropColumn('image_content_type');
            $table->dropColumn('image_blob');
            $table->dropColumn('eepeople_extras');
        });
    }

}
