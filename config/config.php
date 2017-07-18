<?php return [

    /**
     * The CodebaseHQ project slug and key for this app. The slug is in the URL
     * short name of your project. The key is the exceptions project key you can
     * see on the exceptions tab.
     */
    'project' => [
        'slug' => env('CODEBASE_PROJECT_SLUG'),
        'key' => env('CODEBASE_PROJECT_KEY'),
    ],

    /*
     * Toggle to turn off exception logging to codebase. By default, excpetions
     * are logged on all environments but local.
     */
    'log_exceptions' => env('CODEBASE_LOG_EXCEPTIONS', env('APP_ENV') != 'local'),

    /**
     * Your user CodebaseHQ API credentials.  These are displayed on the
     * 'Settings > My Profile' page within the CodebaseHQ site. It is recommended
     * that you do not hard code them, but load load them from your environment.
     */
    'api' => [
        'username' => env('CODEBASE_API_USERNAME'),
        'key' => env('CODEBASE_API_KEY'),
    ],

];
