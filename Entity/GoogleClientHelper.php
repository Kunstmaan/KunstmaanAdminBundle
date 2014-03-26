<?php

namespace Kunstmaan\AdminBundle\Entity;

use Symfony\Component\Yaml\Parser;
use Symfony\Component\Yaml\Dumper;
use \Google_Client;

/**
 * This helper will setup a google api client object
 */
class GoogleClientHelper
{
    /** @var string $path */
    private $path;
    /** @var string $token */
    private $token = false;
    /** @var string $accountId */
    private $propertyId = false;
    /** @var string $accountId */
    private $accountId = false;
    /** @var Google_Client $client */
    private $client;
    /** @var EntityManager $em */
    private $em;

    /**
     * Constructor
     *
     * @param string $clientId
     * @param string $clientSecret
     * @param string $redirectUrl
     * @param string $devKey
     * @param EntityManager $em
     */
    public function __construct($clientId, $clientSecret, $redirectUri, $devKey, $em)
    {
        if ($clientId == "" || $clientSecret == "" || $redirectUri == "" || $devKey == "") {
            throw new \Exception('Google API Parameters not set or incomplete');
        }

        $this->path =  __DIR__ . '/../Resources/config/google.yml';
        $this->em = $em;
        $token = $this->getToken();

        $this->client = new Google_Client();
        $this->client->setApplicationName('Kuma Analytics Dashboard');
        $this->client->setClientId($clientId);
        $this->client->setClientSecret($clientSecret);
        $this->client->setRedirectUri($redirectUri);

        $this->client->setDeveloperKey($devKey);
        $this->client->setScopes(array('https://www.googleapis.com/auth/analytics.readonly'));
        $this->client->setUseObjects(true);

        // if token is already saved in the database
        if ($this->token && $this->token !== '') $this->client->setAccessToken($this->token);
    }

    /**
     * Get the token from the database
     *
     * @return string $token
     */
    private function getToken()
    {
        if (!$this->token) {
            $query = $this->em->createQuery(
              'SELECT c FROM KunstmaanAdminBundle:AnalyticsToken c'
            );
            if ($query->getResult()) $this->token = $query->getResult()[0]->getToken();
        }
        return $this->token;
    }

    /**
     * Save the token to the database
     */
    public function saveToken($token)
    {
        $analyticsToken = new AnalyticsToken();
        $analyticsToken->setToken($token);
        $this->em->persist($analyticsToken);
        $this->em->flush();
    }

    /**
     * Get the propertyId from the database
     *
     * @return string $propertyId
     */
    public function getPropertyId()
    {
        if ($this->propertyId === false) {
            $query = $this->em->createQuery(
              'SELECT c FROM KunstmaanAdminBundle:AnalyticsProperty c'
            );
            if ($query->getResult()) $this->propertyId = $query->getResult()[0]->getPropertyId();
        }
        return $this->propertyId;
    }

    /**
     * Save the token to the database
     */
    public function savePropertyId($id)
    {
        $entity = new AnalyticsProperty();
        $entity->setPropertyId($id);
        $this->em->persist($entity);
        $this->em->flush();
    }

    /**
     * Get the accountId from the database
     *
     * @return string $accountId
     */
    public function getAccountId()
    {
        if ($this->accountId === false) {
            $query = $this->em->createQuery(
              'SELECT c FROM KunstmaanAdminBundle:AnalyticsProperty c'
            );
            if ($query->getResult()) $this->accountId = $query->getResult()[0]->getAccountId();
        }
        return $this->accountId;
    }

    /**
     * Get the client
     *
     * @return Google_Client $client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Check if token is set
     *
     * @return boolean $result
     */
    public function tokenIsSet()
    {
        // return false;
        return $this->getToken() && '' !== $this->getToken();
    }

    /**
     * Check if propertyId is set
     *
     * @return boolean $result
     */
    public function propertyIsSet()
    {
        return false !== $this->getPropertyId() && '' !== $this->getPropertyId();
    }

}
