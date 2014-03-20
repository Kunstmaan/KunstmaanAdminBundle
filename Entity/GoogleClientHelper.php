<?php

namespace Kunstmaan\AdminBundle\Entity;

use Symfony\Component\Yaml\Parser;
use Symfony\Component\Yaml\Dumper;
use GoogleApi\Client;

/**
 * This helper will setup a google api client object
 */
class GoogleClientHelper
{

    private $path;
    private $token = false;
    private $client;
    private $em;

    public function __construct($clientId, $clientSecret, $redirectUri, $devKey, $em) {
        $this->path =  __DIR__ . '/../Resources/config/google.yml';
        $this->em = $em;
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


        if ($this->token && $this->token !== '') {
            $this->client->setAccessToken($this->token);
        }
    }


    private function getToken() {
        if (!$this->token) {
            $query = $this->em->createQuery(
              'SELECT c FROM KunstmaanAdminBundle:AnalyticsToken c'
            );
            if ($query->getResult()) $this->token = $query->getResult()[0]->getToken();
        }
        return $this->token;
    }

    private function saveToken($token) {
        $analyticsToken = new AnalyticsToken();
        $analyticsToken->setToken($token);
        $this->em->persist($analyticsToken);
        $this->em->flush();
    }

    public function getClient() {
        return $this->client;
    }

    public function tokenIsSet() {
        return $this->getToken() && '' !== $this->getToken();
    }

}
