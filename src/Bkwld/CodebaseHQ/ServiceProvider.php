<?php namespace Bkwld\CodebaseHQ;

// Dependencies
use Airbrake;

class ServiceProvider extends \Illuminate\Support\ServiceProvider {

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register() {
		
		// Build the airbrake object for posting exceptions to airbrake
		$this->app->singleton('codebasehq.airbrake', function($app) {
			
			// Settings
			$apiKey  = $app->make('config')->get('codebasehq::api_key');
			$options = array(
				'apiEndPoint' => 'https://exceptions.codebasehq.com/notifier_api/v2/notices',
				'environmentName' => $app->environment(),
				'timeout' => 10, // The default wasn't log enough in my tests
			);
			
			// Instantiate airbrake
			$config = new Airbrake\Configuration($apiKey, $options);
			return new Airbrake\Client($config);
				
		});
		
	}
	
	/**
	 * Boot it up
	 */
	public function boot() {
		$this->package('bkwld/codebasehq');
		$app = $this->app;
		
		// Listen for exception events and pass them to Codebase HQ.
		if ($this->app->make('config')->get('codebasehq::enable')) {
			$this->app->error(function(\Exception $exception) use ($app) {
				
				// Exceptions to ignore
				if (is_a($exception, 'Symfony\Component\HttpKernel\Exception\NotFoundHttpException')) return;
				
				// Tell Codebase
				$app->make('codebasehq.airbrake')->notifyOnException($exception);
			});
		}
		
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides() { 
		return array('codebasehq.airbrake'); 
	}

}