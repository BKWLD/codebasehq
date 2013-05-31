# CodebaseHQ

This is a [Laravel Package](http://laravel.com/) that makes it easy to integrate with [Codebase](http://www.codebasehq.com/) features.  Currently, this means pushing exceptions to [Codebase's Exception](http://blog.atechmedia.com/2012/08/exception-tracking-in-codebase/) tracker but I'd like to add more features if people have suggestions.

## Installation

1. Add to your composer.json's: `"bkwld/codebasehq": "~2.0"`.  Then do a regular composer install.
2. Add as a Laravel provider in app/config/app.php's provider list: `'Bkwld\CodebaseHQ\CodebaseHQServiceProvider',`

## Configuration

* Add the API key for your Codebase project to app/config/packages/bkwld/codebasehq/config.php.  You can get this by visiting the Exceptions view of a Codebase project, you'll see it displayed on the page.  It looks something like "xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx"

## Usage

* 404 errors are currently ignored.  All other exceptions will be posted to Codebase
* By default, your "local" enviornment will not post exceptions to Codebase.  This can be changed in the bundles config/local/codebase.php file.
