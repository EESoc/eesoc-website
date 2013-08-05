## EESoc Website Source Code

Imperial College London EESoc's Offical Website Source Code

### Technologies Used
* [Laravel PHP Framework](http://laravel.com/)
* [Bootstrap 3](http://getbootstrap.com/)

### Installation
1. Rename `app/config/database.sample.php` to `app/config/database.php` and make modifications as necessary.
2. Rename `bootstrap/start.sample.php` to `bootstrap/start.php` and replace `your-machine-name` with whatever the hostname for your machine is. This can be found by using the `hostname` command.
3. Run `php composer.phar install` to download and install dependencies.
4. Run `php artisan migrate` to run database migrations (make sure database has already been created).
5. Enjoy!