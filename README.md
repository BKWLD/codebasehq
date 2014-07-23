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

You will need to edit the published config file, supplying your API creds, for the package to work.  CodebaseHQ has a handfull of different API keys needed for the different services that this package touches:

* To log exceptions, **only** the `exceptions_key` is required
* For either deploy command, your user api creds are needed

## Usage

### Exception Logging

This just works as long as you have supplied the api key.  404 errors are currently ignored.  All other exceptions will be posted to Codebase.  By default, your "local" enviornment will not post exceptions to Codebase.  This can be changed in the published config file at app/config/packages/bkwld/codebasehq/local/config.php.

### Deploy notifications

DeployHQ has a "Deployments" tab, found within your repo, that lists deployments.  You can add to this list using the `codebasehq:deploy` command.  It accepts the following options, which mimic those found in the [API docs](http://support.codebasehq.com/kb/repositories/deployments).

- `branch` - The branch which you are deploying. This defaults to the currently checked out branch.
- `revision` - The reference of the revision/commit you are deploying. It defaults to the HEAD commit.
- `deploy-env` - The environment you are pushing to.
- `servers` **required** - List of servers which you are deploying to (multiple servers should be comma separated, e.g. app1.myapp.com, app2.myapp.com, app3.myapp.com).

Examples:

	php artisan codebasehq:deploy --server=app1.myapp.com
	php artisan codebasehq:deploy --server=production

### Notify tickets of deployments

This command is designed to be run as part of a deploy script and requires you using the CodebaseHQ feature of linking to tickets from commit messages (ex: [touch:12]).  By piping the output from `git log` for the commits you are deploying to `php artisan codebasehq:deploy-tickets`, the package will scan the logs for ticket references and then update those tickets that they have been deployed.  Here's some examples:

	# Get all the commits that aren't on staging/master but are local
	git log staging/master..master | php artisan codebasehq:deploy-tickets
	
	# The same as before, but fetch first so the diff is up to date
	git fetch staging && git log staging/master..master | php artisan codebasehq:deploy-tickets
	
	# Specify which server enviornment you are deploying to
	git fetch staging && git log staging/master..master | php artisan codebasehq:deploy-tickets --server=staging
	
	# Save the the log before you deploy, then tell CodebaseHQ about it after
	git fetch staging && git log staging/master..master > /tmp/deployed-staging-commits.log
	run-deploy-code
	cat /tmp/deployed-staging-commits.log | php artisan codebasehq:deploy-tickets --server=staging
	rm /tmp/deployed-staging-commits.log

Here is an examle of what will get appened to the ticket:

![Deployed message within a ticket](http://f.cl.ly/items/342g2T0a04103m031q0Q/PNG.png)
	
