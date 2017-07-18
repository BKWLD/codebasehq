# CodebaseHQ

This is a [Laravel Package](http://laravel.com/) that makes it easy to integrate with select [Codebase](http://www.codebasehq.com/) features:

* Pushing of exceptions, including full stack trace and all environment variables.
* A command that can be used in a deploy script to log a deployment.
* A command that can be used to comment on all tickets that were referenced in deployed git commit logs.

## Installation

1. Add it to your composer.json (`"bkwld/codebasehq": "dev-master"`) and do a composer install.
2. Add the service provider to your app.php config file providers: `Bkwld\CodebaseHQ\ServiceProvider::class,`.


## Configuration

You will need to supply credentials to your CodebaseHQ account for this package to work. You can either store the following in your `.env` file (preferred) or publish and edit the config file for this package (`php artisan vendor:publish --provider=="Bkwld\CodebaseHQ\ServiceProvider"`).

- To log exceptions, **only** the `project` configs is required
- For either deploy command, your user `api` creds are needed

#### .env configuration

```bash
# CodebaseHQ settings
CODEBASE_PROJECT_SLUG=
CODEBASE_PROJECT_KEY=
CODEBASE_API_USERNAME=
CODEBASE_API_KEY=
CODEBASE_LOG_EXCEPTIONS=
```


## Usage

### Exception Logging

This package listens for Laravel log events and pushes errors to CodebaseHQ.  By default, exceptions fired from a `local` environment are not sent to CodebaseHQ.  This can be changed by setting `CODEBASE_LOG_EXCEPTIONS` explicitly to `true` in your local `.env` file.  You can control which exceptions *don't* get sent to CodebaseHQ by editing your app's `App\Exceptions\Handler::$dontReport` variable.

### Deploy notifications

DeployHQ has a "Deployments" tab, found within your repo, that lists deployments.  You can add to this list using the `codebasehq:deploy` command.  It accepts the following options, which mimic those found in the [API docs](http://support.codebasehq.com/kb/repositories/deployments).

- `servers` **required** - List of servers which you are deploying to (multiple servers should be comma separated, e.g. app1.myapp.com, app2.myapp.com, app3.myapp.com).
- `branch` - The branch which you are deploying. This defaults to the currently checked out branch.
- `revision` - The reference of the revision/commit you are deploying. It defaults to the HEAD commit.
- `deploy-env` - The environment you are pushing to.

Examples:

	php artisan codebasehq:deploy app1.myapp.com
	php artisan codebasehq:deploy production --branch=production

### Notify tickets of deployments

This command is designed to be run as part of a deploy script and requires you using the CodebaseHQ feature of linking to tickets from commit messages (ex: [touch:12]).  By piping the output from `git log` for the commits you are deploying to `php artisan codebasehq:deploy-tickets`, the package will scan the logs for ticket references and then update those tickets that they have been deployed.  Here's some examples:

	# Get all the commits that aren't on staging/master but are local
	git log staging/master..master | php artisan codebasehq:deploy-tickets

	# The same as before, but fetch first so the diff is up to date
	git fetch staging && git log staging/master..master | php artisan codebasehq:deploy-tickets

	# Specify which server environment you are deploying to
	git fetch staging && git log staging/master..master | php artisan codebasehq:deploy-tickets --server=staging

	# Save the the log before you deploy, then tell CodebaseHQ about it after
	git fetch staging && git log staging/master..master > /tmp/deployed-staging-commits.log
	run-deploy-code
	cat /tmp/deployed-staging-commits.log | php artisan codebasehq:deploy-tickets --server=staging
	rm /tmp/deployed-staging-commits.log

Here is an example of what will get appended to the ticket:

![Deployed message within a ticket](http://f.cl.ly/items/342g2T0a04103m031q0Q/PNG.png)
