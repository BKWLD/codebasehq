<?php namespace Bkwld\CodebaseHQ;

/**
 * Resuable code for submitting to the CodebaseHQ API
 */
class Request {

    /**
     * Inject dependencies
     * @param string $user API Username
     * @param string $key API Key
     * @param string $project API Project
     */
    public function __construct($user, $key, $project) {
        $this->user = $user;
        $this->key = $key;
        $this->project = $project;
    }

    /**
     * Make a request on the CodebaseHQ API
     *
     * @return void
     * @throws Bkwld\CodebaseHQ\Exception
     */
    public function call($method, $path, $xml)
    {

        // Default headers
        $headers = array('Accept: application/xml', 'Content-type: application/xml');

        // Create basic-auth syntax
        $auth = $this->user.':'.$this->key;

        // Endpoint
        $path = trim($path, '/');
        $url = 'https://api3.codebasehq.com/'.$this->project.'/'.$path;

        // Make request
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_USERPWD, $auth);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        // If not a 20# status, it's a failure
        if (!preg_match('#^20\d$#', $status)) {
            throw new Exception("CodebaseHQ request failure ({$status}): {$result}");
        }

    }

}
