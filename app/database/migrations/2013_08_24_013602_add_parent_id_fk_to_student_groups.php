<?php

use Illuminate\Database\Migrations\Migration;

class AddParentIdFkToStudentGroups extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('student_groups', function($table) {
            $table->dropColumn('parent_id');
        });
        Schema::table('student_groups', function($table) {
            $table->integer('parent_id')->unsigned()->nullable()->default(null);
            $table->foreign('parent_id')->references('id')->on('student_groups');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('student_groups', function($table) {
            $table->dropForeign('student_groups_parent_id_foreign');
        });
    }

}
