<?php

use Illuminate\Database\Migrations\Migration;

class LessChristmas extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        $this->doRename("christmas_", "", "dinner_");
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        $this->doRename("", "christmas_", "dinner_");
	}

    protected function doRename($prefixFrom, $prefixTo, $prefixBoth)
    {
        foreach ($this->tables as $table)
            Schema::rename("$prefixFrom$prefixBoth$table", "$prefixTo$prefixBoth$table");
    }

    protected $tables = ['group_members', 'groups', 'sales'];
}
