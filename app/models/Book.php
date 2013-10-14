<?php

class Book extends Eloquent {

	protected $fillable = ['google_book_id', 'isbn', 'name', 'condition', 'target_student_groups', 'target_course', 'price', 'quantity', 'contact_instructions', 'expires_at'];

	protected $softDelete = true;

}