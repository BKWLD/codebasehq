<?php return array(
	
	/**
	 * The CodebaseHQ project associated with this Laravel app.  This is the short name
	 * that is used in the URL of the CodebaseHQ project.
	 */
	'project' => 'xxxxxx',
	
	/**
	 * Your user CodebaseHQ API credentials.  These are displayed on the 'Settings > My Profile'
	 * page within the CodebaseHQ site.
	 */
	'api' => array(
		'username' => 'account_name/username',
		'key' => 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxx',
	),
	
	/* 
	 * The Exceptions API key for your Codebase project.  You can get this by visiting 
	 * the Exceptions view of a Codebase project, you'll see it displayed on the page. 
	 */
	'exceptions_key' => 'xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx',
	
	/**
	 * The Deploys API key for your repo.  This can be found on the "Deployments" tab
	 * of your repository for this Laravel app.
	 */
	'deployments_key' => 'xxxxxxxxxxxxxxxx',
	
	/*
	 * Toggle to turn off exception logging to codebase. This can be overriden in
	 * enviornment specific config files
	 */
	'exception_logging' => true,
);