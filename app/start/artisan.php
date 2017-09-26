<?php

/*
|--------------------------------------------------------------------------
| Register The Artisan Commands
|--------------------------------------------------------------------------
|
| Each available Artisan command must be registered with the console so
| that it is available to be called. We'll register every command so
| the console gets access to each of the command object instances.
|
*/

Artisan::add(new BecomeAdminCommand);
Artisan::add(new InstagramSyncCommand);
Artisan::add(new RemindUnclaimedLockerCommand);
Artisan::add(new SendEmailsCommand);
Artisan::add(new SendLockerTermsAndConditionsCommand);
Artisan::add(new SendDinnerEmailsCommand);
Artisan::add(new SendTestEmailCommand);
Artisan::add(new SyncEActivitiesCommand);
Artisan::add(new SyncEActivitiesSalesCommand);
Artisan::add(new SyncEEPeopleCommand);
Artisan::add(new SyncLDAPCommand);
Artisan::add(new ImportMumsAndDadsCommand);
Artisan::add(new ImportBooksISBNCommand);
Artisan::add(new SendLockerIssuesCommand);
