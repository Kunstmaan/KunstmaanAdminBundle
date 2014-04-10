<?php

namespace Kunstmaan\AdminBundle\Helper;

use \Google_Client;
use Kunstmaan\AdminBundle\Entity\AnalyticsToken;
use Symfony\Cmf\Component\Routing\ChainRouter;

/**
 * This helper will setup a google api client object
 */
class GoogleClientHelper
{
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
     * @param string        $clientId
     * @param string        $clientSecret
     * @param string        $redirectUrl
     * @param string        $devKey
     * @param EntityManager $em
     */
    public function __construct($googleClient, $em)
    {
        $this->client       = $googleClient;
        $this->em           = $em;

        $this->init();
    }

    /**
     * Tries to initialise the Client object
     *
     * @throws Exception when API parameters are not set or incomplete
     */
    public function init()
    {
        // if token is already saved in the database
        if ($this->getToken() && '' !== $this->getToken()) {
            $this->client->setAccessToken($this->token);
        }
    }

    /**
     * sets the redirect URI of the API client
     *
     * @param ChainRouter $router
     * @param string $routeName
     */
    public function setRedirectUri(ChainRouter $router, $routeName) {
        $uri = $router->generate($routeName, array(), true);
        $this->client->setRedirectUri($uri);
    }

    /**
     * Get the token from the database
     *
     * @return string $token
     */
    private function getToken()
    {
        if (!$this->token) {
            $this->token = $this->em->getRepository('KunstmaanAdminBundle:AnalyticsConfig')->getConfig()->getToken();
        }

        return $this->token;
    }

    /**
     * Get the accountId from the database
     *
     * @return string $accountId
     */
    public function getAccountId()
    {
        if (!$this->accountId) {
            $this->accountId = $this->em->getRepository('KunstmaanAdminBundle:AnalyticsConfig')->getConfig()->getAccountId();
        }

        return $this->accountId;
    }

    /**
     * Get the propertyId from the database
     *
     * @return string $propertyId
     */
    public function getPropertyId()
    {
        if (!$this->propertyId) {
            $this->propertyId = $this->em->getRepository('KunstmaanAdminBundle:AnalyticsConfig')->getConfig()->getPropertyId();
        }

        return $this->propertyId;
    }

    /**
     * Save the token to the database
     */
    public function saveToken($token)
    {
        $this->token = $token;
        $this->em->getRepository('KunstmaanAdminBundle:AnalyticsConfig')->saveToken($token);
    }

    /**
     * Save the accountId to the database
     */
    public function saveAccountId($accountId)
    {
        $this->accountId = $accountId;
        $this->em->getRepository('KunstmaanAdminBundle:AnalyticsConfig')->saveAccountId($accountId);
    }

    /**
     * Save the propertyId to the database
     */
    public function savePropertyId($propertyId)
    {
        $this->propertyId = $propertyId;
        $this->em->getRepository('KunstmaanAdminBundle:AnalyticsConfig')->savePropertyId($propertyId);
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
