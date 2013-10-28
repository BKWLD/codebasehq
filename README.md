# CodebaseHQ

This is a [Laravel Package](http://laravel.com/) that makes it easy to integrate with select [Codebase](http://www.codebasehq.com/) features:

* Pushing of exceptions, including full stack trace and all enviornment variables.
* A command that can be used in a deploy script to log a deployment.
* A command that can be used to comment on all tickets that were refrenced in deployed git commit logs.

## Installation

1. Add it to your composer.json (`"bkwld/codebasehq": "~3.0"`) and do a composer install.
2. Add the service provider to your app.php config file providers: `'Bkwld\CodebaseHQ\ServiceProvider',`.
3. Push config files to your app/config/packages directory for customization with `php artisan config:publish bkwld/codebasehq`.  This is required for Laravel to respect different config settings per enviornment.

## Configuration

* Add the API key for your Codebase project to app/config/packages/bkwld/codebasehq/config.php.  You can get this by visiting the Exceptions view of a Codebase project, you'll see it displayed on the page.  It looks something like "xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx"

## Usage

### Exception Logging

* 404 errors are currently ignored.  All other exceptions will be posted to Codebase
* By default, your "local" enviornment will not post exceptions to Codebase.  This can be changed in the published config file at app/config/packages/bkwld/codebasehq/local/config.php.

### Deploy notifications



### Deployed tickets via git commits

