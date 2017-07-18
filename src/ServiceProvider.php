<?php namespace Bkwld\CodebaseHQ;

// Dependencies
use Airbrake\Notifier;
use Illuminate\Log\Events\MessageLogged;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider {

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        // Merges package config with user config
		$this->mergeConfigFrom(__DIR__.'/../config/config.php', 'codebasehq');

        // Build the airbrake object for posting exceptions to airbrake
        $this->app->singleton('codebasehq.airbrake', function($app) {
            return new Notifier([
                'projectId' => config('codebasehq.project.slug'),
                'projectKey' => config('codebasehq.project.key'),
                'host' => 'https://exceptions.codebasehq.com',
            ]);
        });

        // Build the Request object which talks to codebase
        $this->app->singleton('codebasehq.request', function($app) {
            return new Request(
                config('codebasehq.api.username'),
                config('codebasehq.api.key'),
                config('codebasehq.project.slug')
            );
        });

        // Register commands
        $this->app->singleton('command.codebasehq.deploy', function($app) {
            return new Commands\Deploy($app->make('codebasehq.request'));
        });
        $this->app->singleton('command.codebasehq.deploy_tickets', function($app) {
            return new Commands\DeployTickets($app->make('codebasehq.request'));
        });
        $this->commands([
            'command.codebasehq.deploy',
            'command.codebasehq.deploy_tickets'
        ]);

    }

    /**
     * Boot it up
     */
    public function boot()
    {
        // Registers the config file for publishing to app directory
		$this->publishes([
			__DIR__.'/../config/config.php' => config_path('codebasehq.php')
		], 'codebasehq');

        // Add listener of errors
        if (config('codebasehq.log_exceptions')) {
            $this->listenForAndLogErrors();
        }
    }

    /**
     * Listen and log excetpions to Codebase
     *
     * @return void
     */
    public function listenForAndLogErrors()
    {
        // Listen for log events
        $logger = $this->app->make('Psr\Log\LoggerInterface');
        $logger->listen(function(MessageLogged $log) {
            $this->onLog($log);
        });
    }

    /**
     * Handle log events
     *
     * @param  MessageLogged $log
     * @return void
     */
    protected function onLog(MessageLogged $log)
    {
        // Check that the message is an exception
        if (!is_a($log->message, \Exception::class)) {
            return;
        }

        // Check that the exception is not on don't report list
        $error = $log->message;
        $handler = $this->app->make('App\Exceptions\Handler');
        if (!$handler->shouldReport($error)) {
            return;
        }

        // Sent to codebase
        $this->app->make('codebasehq.airbrake')->notify($error);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            'codebasehq.airbrake',
            'codebasehq.request',
            'command.codebasehq.deploy',
            'command.codebasehq.deploy_tickets',
        ];
    }

}
