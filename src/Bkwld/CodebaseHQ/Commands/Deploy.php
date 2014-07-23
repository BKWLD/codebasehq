<?php namespace Bkwld\CodebaseHQ\Commands;

// Dependencies
use Illuminate\Console\Command;
use SimpleXMLElement;
use Symfony\Component\Console\Input\InputOption;

class Deploy extends Command {
	
	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'codebasehq:deploy';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Trigger a deploy notification on CodebaseHQ';

	/**
	 * The options
	 */
	protected function getOptions() {
		return array(
			array('branch', 'b', InputOption::VALUE_OPTIONAL, 'The branch which you are deploying'),
			array('revision', 'r', InputOption::VALUE_OPTIONAL, 'The reference of the revision/commit you are deploying'),
			array('deploy-env', null, InputOption::VALUE_OPTIONAL, 'The environment you are pushing to'),
			array('servers', 's', InputOption::VALUE_REQUIRED, 'List of servers (comma seperated) which you are deploying to'),
		);
	}
	
	/**
	 * Inject dependencies
	 * @param Bkwld\CodebaseHQ\Request $request
	 */
	public function __construct($request) {
		$this->request = $request;
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return void
	 */
	public function fire() {
		
		// Get options
		$branch = $this->option('branch');
		$revision = $this->option('revision');
		$environment = $this->option('deploy-env');
		$servers = $this->option('servers');
		
		// Create defaults by talking to git
		if (!$branch) $branch = trim(`git rev-parse --abbrev-ref HEAD`);
		if (!$revision) $revision = trim(`git rev-parse HEAD`);
		
		// Create XML
		$xml = new SimpleXMLElement('<deployment/>');
		$xml->addChild('branch', $branch);
		$xml->addChild('revision', $revision);
		$xml->addChild('environment', $environment);
		$xml->addChild('servers', $servers);
		
		// Get the name of the repo
		preg_match('#/([\w-]+)\.git$#', trim(`git config --get remote.origin.url`), $matches);
		$repo = $matches[1];

		// Send request
		$this->request->call('POST', $repo.'/deployments', $xml->asXML());
		
		// Ouptut status
		$this->info('CodebaseHQ has been notified of the deployment');
	}
	
}