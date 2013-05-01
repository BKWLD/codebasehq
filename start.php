<?php


// Listen for exception events and pass them to Codebase HQ.  Using this rather than
// listening to log events so we can get the full exception stack trace rather than the
// summary that gets truncated by exception_line()
if (Config::get('codebasehq::enable')
	&& Config::get('error.log')) {
	$callback = Config::get('error.logger');
	Config::set('error.logger', function($exception) use ($callback) {

		// Pass error to Codebase through Airbrake interface
		$airbrake = IoC::resolve('airbrake');
		$airbrake->notifyOnException($exception);
		
		// Call previous registered function
		$callback($exception);
		
	});
}
