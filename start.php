<?php

// Bootstrap the airbrake connector to codebase
IoC::singleton('airbrake', function() {
	
	// Load dependencies
	require_once 'vendor/php-airbrake/src/Airbrake/Client.php';
	require_once 'vendor/php-airbrake/src/Airbrake/Configuration.php';

	// Settings
	$apiKey  = Config::get('laravel-plus-codebase::codebase.api_key');
	$options = array(
		'apiEndPoint' => 'https://exceptions.codebasehq.com/notifier_api/v2/notices',
		'environmentName' => Request::env(),
		'timeout' => 10, // The default wasn't log enough in my tests
	);

	// Instantiate the class
	$config = new Airbrake\Configuration($apiKey, $options);
	$client = new Airbrake\Client($config);

	// Return a a reference to the Airbrake client
	return $client;
});

// Listen for exception events and pass them to Codebase HQ.  Using this rather than
// listening to log events so we can get the full exception stack trace rather than the
// summary that gets truncated by exception_line()
Config::set('error.logger', function($exception) {

	// Don't post if logging is turned off
	if (!Config::get('error.log')) return;

	// Pass error to Codebase through Airbrake interface
	$airbrake = IoC::resolve('airbrake');
	$airbrake->notifyOnException($exception);
	
});