<?php

use Illuminate\Database\Migrations\Migration;

class CreateBooksTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('books', function($table) {
			$table->increments('id');
			$table->integer('user_id')->unsigned();
			$table->foreign('user_id')->references('id')->on('users');

			// Google books API
			$table->string('isbn');
			$table->string('google_book_id');
			$table->string('name');
			$table->text('authors');
			$table->text('categories');
			$table->string('thumbnail');
			$table->text('snippets');

			// EESoc stuff
			$table->string('status');
			$table->string('condition');
			$table->string('target_course');
			$table->integer('price_in_pence')->unsigned();
			$table->integer('quantity')->unsigned()->default(1);
			$table->text('contact_instructions');

			// Important dates
			$table->date('expires_at');
			$table->softDeletes();
			$table->timestamps();
		});

		Schema::create('books_student_groups', function($table) {
			$table->integer('book_id')->unsigned();
			$table->foreign('book_id')->references('id')->on('books');
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
		Schema::drop('books_student_groups');
		Schema::drop('books');
	}

}