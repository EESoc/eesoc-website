<?php

/*
 * Imperial Auth Extension
 */
Auth::extend('imperialcollege', function() {
	$userProvider = new ImperialCollegeUserProvider(Config::get('auth.model'));
	return new Illuminate\Auth\Guard($userProvider, App::make('session'));
});

/*
 * Blade Extensions
 */

/*
 * Content tags: @content('slug')
 */
Blade::extend(function($value, $blade) {
	$pattern = $blade->createMatcher('content');

	return preg_replace($pattern, '$1<?php echo \Content::fetch$2; ?>', $value);
});