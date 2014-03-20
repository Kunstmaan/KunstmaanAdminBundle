<?php

namespace Kunstmaan\AdminBundle\Helper;

use Symfony\Component\Yaml\Parser;
use Symfony\Component\Yaml\Dumper;
use GoogleApi\Client;

/**
 * This helper will help setup a google analytics objects
 */
class GoogleClientHelper
{

    private const PATH = __DIR__.'/../../../../vendor/kunstmaan/admin-bundle/Kunstmaan/AdminBundle/Resources/config/google.yml';
    private $token = false;
    private $client;

    public function __construct($clientId, $clientSecret, $redirectUri, $devKey) {
        $token = $this->getToken();

        $this->client = new Client();
        $this->client->setApplicationName('Kuma Analytics Dashboard');
        $this->client->setClientId($clientId);
        $this->client->setClientSecret($clientSecret);
        $this->client->setRedirectUri($redirectUri);
        $this->client->setDeveloperKey($devKey);
        $this->client->setScopes(array('https://www.googleapis.com/auth/analytics.readonly'));
        $this->client->setUseObjects(true);

        if (isset($_GET['code'])) {
            $this->client->authenticate();
            $token = $this->client->getAccessToken();

            // save the token
            $this->saveToken($token);

            $redirect = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
            header('Location: ' . filter_var($redirect, FILTER_SANITIZE_URL));
            exit;
        }

        if ($this->$token !== null && $this->$token !== '') {
            $this->client->setAccessToken($this->$token);
        }
    }


    private static function getToken() {
        if (!$this->$token) {
            $parser = new Parser();
            $this->$token = $parser->parse(file_get_contents(self::PATH))['google.api.access_token'];
        }
        return $this->$token;
    }

    private function saveToken($token) {
        $param = ['google.api.access_token' => $token];
        $dumper = new Dumper();
        $yaml = $dumper->dump($param);
        file_put_contents(self::PATH, $yaml);
    }

    public function getClient() {
        return $this->client;
    }

}
