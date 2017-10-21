## EESoc Website Source Code

[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%205.5-8892BF.svg)](https://php.net/)
[![Build Status](https://travis-ci.org/hsed/eesoc-website.svg?branch=master)](https://travis-ci.org/hsed/eesoc-website)

Imperial College London EESoc's Offical Website Source Code

### Major Technologies Used
* [Laravel 4.0.7](http://laravel.com/)
* [Bootstrap 3.0.0](http://getbootstrap.com/)

### Installation
1. Rename `app/config/database.sample.php` to `app/config/database.php` and make modifications as necessary.
2. Rename `bootstrap/start.sample.php` to `bootstrap/start.php` and replace `your-machine-name` with the hostname of your local machine is. This can be found by the `hostname` command.
3. Install Composer by running `curl -sS https://getcomposer.org/installer | php`
4. Run `php composer.phar install` to download and install dependencies.
5. Run `php artisan migrate` to run database migrations (make sure database has already been created).
6. If you're on PHP 5.4, run `php artisan serve` to start the built-in web server.
7. Enjoy!

### Commands

- `php artisan admin:become <imperial-college-login>` - Promotes a user to admin.
- `php artisan eactivities:sales:sync` - Sync online sales.
- `php artisan eactivities:sync` - Sync membership with EActivities.
- `php artisan eepeople:sync` - Sync user info with the departmental directory.
- `php artisan instagram:sync` - Syncs Instagram feed.
- `php artisan ldap:sync` - Sync user info with LDAP.
- `php artisan locker:remind` - Remind users via email to claim their locker.
- `php artisan locker:send_terms` - Send locker terms and conditions to all owners.

### Writeable folders

Certain folders within the repo must be write-able by the web process in order for the site to operate. They are:
- /app/storage
- /public/files
- /public/assets 


### TODO

Heres the todo for 2017/18, most items may not be completable due to time constraints but should be looked into in future.
- [X] Use new API from union for sync functions
- [ ] Upgrade laravel at least to level of PHP5.5
- [ ] Upgrade bootstrap to v4
- [ ] Create user/admin dashboard
- [ ] Create minimalist + responsive public pages
- [ ] Re-design website
- [ ] Create custom subscribable lists for categorical newsletters
