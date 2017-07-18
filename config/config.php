<?php return array(

    /**
     * The CodebaseHQ project associated with this Laravel app.  This is the short
     * name that is used in the URL of the CodebaseHQ project.
     */
    'project' => 'xxxxxx',

    /**
     * Your user CodebaseHQ API credentials.  These are displayed on the
     * 'Settings > My Profile' page within the CodebaseHQ site. It is recommended
     * that you do not hard code them, but load load them from your environment.
     */
    'api' => array(
        'username' => @$_ENV['CODEBASE_USERNAME'],
        'key' => @$_ENV['CODEBASE_KEY'],
    ),

    /*
     * The Exceptions API key for your Codebase project.  You can get this by
     * visiting  the Exceptions view of a Codebase project, you'll see it
     * displayed on the page.
     */
    'exceptions_key' => 'xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx',

    /*
     * Toggle to turn off exception logging to codebase. This can be overriden in
     * enviornment specific config files
     */
    'exception_logging' => true,

    /**
     * Exceptions that shouldn't be reported to Codebase
     */
    'ignored_exceptions' => array(
        'Illuminate\Database\Eloquent\ModelNotFoundException',
        'Illuminate\Session\TokenMismatchException',
        'Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException',
        'Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException',
        'Symfony\Component\HttpKernel\Exception\NotFoundHttpException',
        'Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException',
    ),
);
