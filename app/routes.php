<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

# Home
Route::controller('home', 'BetaController');

Route::get('/', function()
{
    //essentially the concept is that the user views a beta page if not logged in
    //or else the dashboard if logged in, old welcome page will never be shown
    if (is_null(Auth::user())){
        //return Redirect::intended(URL::action('HomeController@getWelcome'));
        return Redirect::to('https://eesoc.com/home');
    }
    else {
        if(Auth::user()->is_admin){
            //return Redirect::intended(URL::action('HomeController@getWelcome'));
            return Redirect::to('https://eesoc.com/admin');
        }
        else {
            return Redirect::to('https://eesoc.com/dashboard');
        }
       
    }

});
// Route::get('/', 'BetaController@getIndex');
// // });
// Route::group(['before' => ''], function() {
//     Route::get('/', 'BetaController@getIndex');
//     //Route::get('/', 'HomeController@getWelcome');
// });
// Route::group(['middleware' => 'auth.member'], function() {
//     Route::get('/', 'HomeController@getWelcome');
// });


// Route::group(['middleware' => 'auth.guest'], function() {
//     // Route::get('/', function()
//     // {
//     //     return Redirect::to('https://eesoc.com/beta');
//     // });
//     Route::get('/', 'BetaController@getIndex');
// });
// Route::group(['middleware' => 'auth.member'], function() {
//     Route::get('/', function() {
//         return Redirect::to('https://eesoc.com/admin');
//     });
// });
// Route::get('/', function()
// {
//     return Redirect::to('https://eesoc.com/beta');
// });


# Session Management
Route::get('sign-in',     'SessionsController@getNew');
Route::post('sign-in',    'SessionsController@postCreate');
Route::delete('sign-out', 'SessionsController@deleteDestroy');

# Cron
Route::controller('cron', 'CronController');

# Emails
Route::get('emails/track/{tracker_token}.gif', 'EmailsController@getTrack');
Route::controller('emails', 'EmailsController');

# Events
Route::controller('events', 'EventsController');

# Newsletter
Route::controller('newsletters', 'NewslettersController');

# Sponsors
Route::controller('sponsors', 'SponsorsController');

# Careers Fair
Route::controller('careersfair', 'CareersFairController');

# Beta
# Events
//Route::controller('beta', 'BetaController');

# API
# API
Route::controller('api/v2', 'ApiV2Controller');
Route::post('api/v2/event', 'ApiV2Controller@postEvent');

# Oauth
Route::post('oauth/access_token', ['uses' => 'OAuthController@postAccessToken']);
Route::get('oauth/authorize', ['before' => 'check-authorization-params|auth', 'uses' => 'OAuthController@getAuthorize']);
Route::get('api/me', ['before' => 'oauth:basic', 'uses' => 'ApiController@getMe']);

#Event API
Route::post('api/event/cid', 'ApiController@postEventCID');
Route::post('api/event/name', 'ApiController@postEventName');

# TV
Route::get('tv', 'TVController@show');

# Bar Night 2017 (temp redirect)
Route::get('barnight', function()
{
    return Redirect::to('https://www.imperialcollegeunion.org/shop/club-society-project-products/electrical-engineering-products/19111/eesoc-bar-night-tickets');
});

# Sponsor Link
Route::get('bae', function()
{
    return Redirect::to('https://career012.successfactors.eu/career?company=BAE&site=VjItSE43VDBudHJlU3UwSGpKcUVacWFRQT09', 303, ['X-Why' => 'Yes']);
});

//Temp short linking -- now done properly via db
// Route::get('dinner', function()
// {
//     return Redirect::to('https://www.imperialcollegeunion.org/shop/club-society-project-products/electrical-engineering-products/19974/eesoc-new-years-dinner-ticket-members', 302);
// });
// Route::get('dinner/non-member', function()
// {
//     return Redirect::to('https://www.imperialcollegeunion.org/shop/club-society-project-products/electrical-engineering-products/19973/eesoc-new-years-dinner-ticket-non-members', 302);
// });
// Route::get('dinner/staff', function()
// {
//     return Redirect::to('https://www.imperialcollegeunion.org/shop/club-society-project-products/electrical-engineering-products/19975/eesoc-new-years-dinner-ticket-staff', 302);
// });
// Route::get('dinner/afterparty', function()
// {
//     return Redirect::to('https://www.imperialcollegeunion.org/shop/club-society-project-products/electrical-engineering-products/20003/eesoc-new-years-dinner-afterparty-ticket-members-only', 302);
// });
Route::get('mumsanddads', function()
{
    return Redirect::to('https://eesoc.com/mums-and-dads', 302);
});
Route::get('mums_and_dads', function()
{
    return Redirect::to('https://eesoc.com/mums-and-dads', 302);
});

Route::get('locker', function()
{
    return Redirect::to('https://eesoc.com/dashboard/lockers', 302);
});
Route::get('lockers', function()
{
    return Redirect::to('https://eesoc.com/dashboard/lockers', 302);
});

/**
 * Routes for members
 */
Route::group(['before' => 'auth.member'], function() {
    # default view for members
    //Route::get('/', 'HomeController@getWelcome');

    # Dashboard sub-links
    Route::group(['prefix' => 'dashboard'], function() {
        # Subscriptions
       
        
        # Books
        Route::resource('books', 'BooksController');
        
        # Lockers
        Route::controller('lockers', 'LockersController');

        # Dinner
        Route::group(['prefix' => 'dinner'], function() {
            # Groups
            Route::resource('groups', 'DinnerGroupsController');
            Route::post('update-menu', ['uses' => 'DinnerGroupsController@updateMenuChoice']);
            Route::post('remove-member', ['uses' => 'DinnerGroupsController@removeMember']);
            Route::post('add-member', ['uses' => 'DinnerGroupsController@addMember']);
        });

        Route::post('update', ['uses' => 'UsersController@updateSubscription']);
    });

    # All direct function calls must be defined AFTER sub-links are defined    
    Route::controller('dashboard', 'UsersController');
    
    #Mums and Dads
    Route::controller('mums-and-dads', 'MumsAndDadsController');

    
});

/**
 * Routes for admins
 */
Route::group(['before' => 'auth.admin', 'prefix' => 'admin'], function() {

    Route::get('/', 'Admin\DashboardController@getShow');

    # Categories
    Route::resource('categories', 'Admin\CategoriesController', ['except' => ['show']]);

    # Dinner Tickets
    Route::controller('dinner-tickets', 'Admin\DinnerTicketsController');

    # Contents
    Route::resource('contents', 'Admin\ContentsController', ['except' => ['show']]);

    # Emails
    Route::resource('emails', 'Admin\EmailsController');
    Route::controller('emails', 'Admin\EmailsController');

    # Events
    Route::resource('events', 'Admin\EventsController', ['except' => ['show']]);
    Route::controller('events', 'Admin\EventsController');

    # Instagram Photos
    Route::controller('instagram-photos', 'Admin\InstagramPhotosController');

    # Logs
    Route::resource('logs', 'Admin\LogsController', ['only' => ['index', 'show']]);

    # Newsletters
    // Route::resource('newsletters', 'Admin\NewslettersController');

    # Pages
    Route::resource('pages', 'Admin\PagesController', ['except' => ['show']]);

    # Posts
    Route::resource('posts', 'Admin\PostsController', ['except' => ['show']]);

    # Sales
    #Route::resource('sales', 'Admin\SalesController');
    Route::controller('sales', 'Admin\SalesController');

    # Sponsors
    Route::resource('sponsors', 'Admin\SponsorsController', ['except' => ['show']]);

    # Committee
    Route::resource('committee', 'Admin\CommitteeController', ['except' => ['show']]);

    # Short Links
    Route::resource('links', 'Admin\LinksController', ['except' => ['show']]);
    Route::controller('links', 'Admin\LinksController');

    # Careers Fair
    Route::resource('careersfair', 'Admin\CareersFairController', ['except' => ['show']]);

    # Tests
    Route::controller('tests', 'Admin\TestsController');

    # User Sign Ins
    Route::resource('user-sign-ins', 'Admin\UserSignInsController', ['only' => ['index']]);

    # Users
    Route::controller('users/eactivities', 'Admin\UsersEActivitiesController');
    Route::controller('users/eepeople',    'Admin\UsersEEPeopleController');
    Route::controller('users',             'Admin\UsersController');
    Route::resource  ('users',             'Admin\UsersController', ['only' => ['index']]);

    # elFinder
    Route::get('elfinder',           'Barryvdh\ElfinderBundle\ElfinderController@showIndex');
    Route::any('elfinder/connector', 'Barryvdh\ElfinderBundle\ElfinderController@showConnector');
    Route::get('elfinder/ckeditor',  'Barryvdh\ElfinderBundle\ElfinderController@showCKEditor');
});


// Link::active()
//             ->get();

# Catch all
Route::any('{path}', function($path) {
    $path = rtrim($path, '/');
    $link = null;
    $page = Page::where('slug', '=', $path)->first();

    if ( ! $page) {
        $link = Link::active()->where('slug', '=', $path)->first();
    }

    
    if ( ! $page && ! $link) {
        App::abort(404);
    }
    elseif ($link) {
        return Redirect::to($link->full_url);
    }
    else {
        return View::make('page')
        ->with('page', $page);
    }

    
})->where('path', '.*');

App::missing(function($exception)
{
    return Response::view('errors.missing', array(), 404);
});
