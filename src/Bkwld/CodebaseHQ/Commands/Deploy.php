<?php namespace Bkwld\CodebaseHQ\Commands;

// Dependencies
use Illuminate\Console\Command;

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
	 * Execute the console command.
	 *
	 * @return void
	 */
	public function fire() {
		
		
	}
	
}