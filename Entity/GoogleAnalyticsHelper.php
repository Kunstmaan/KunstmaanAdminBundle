<?php

namespace Kunstmaan\AdminBundle\Entity;

use \Google_Client;
use \Google_AnalyticsService;

/**
 * This helper will setup a google analytics object
 */
class GoogleAnalyticsHelper
{
    /** @var Google_AnalyticsService $analytics */
    private $analytics;
    /** @var GoogleClientHelper $clientHelper */
    private $clientHelper;

    /**
     * Initialize the clientHelper and analyticsService
     *
     * @param GoogleClientHelper $clientHelper
     */
    public function init(GoogleClientHelper $clientHelper)
    {
        $this->clientHelper = $clientHelper;
        $this->analytics = new Google_AnalyticsService($this->clientHelper->getClient());
    }

    /**
     * Get the profile ID
     *
     * @throws Exception if no accounts, webproperties or views are found
     */
    public function getProfileId()
    {
        $accounts = $this->analytics->management_accounts->listManagementAccounts();
        if (count($accounts->getItems()) > 0) {
            $items = $accounts->getItems();
            $firstAccountId = $items[0]->getId();

            $webproperties = $this->analytics->management_webproperties->listManagementWebproperties($firstAccountId);

            if (count($webproperties->getItems()) > 0) {
                $items = $webproperties->getItems();
                $profiles = $this->analytics->management_profiles->listManagementProfiles($this->clientHelper->getAccountId(), $this->clientHelper->getPropertyId());

                if (count($profiles->getItems()) > 0) {
                    $items = $profiles->getItems();
                    return $items[0]->getId();

                } else {
                    throw new \Exception('No views (profiles) found for this user.');
                }
            } else {
                throw new \Exception('No webproperties found for this user.');
            }
        } else {
            throw new \Exception('No accounts found for this user.');
        }
    }

    /**
     * Get a list of all available properties for a Google Account
     *
     * @return array $data A list of all properties
     */
    public function getProperties()
    {
        $data = array();
        $accounts = $this->analytics->management_accounts->listManagementAccounts()->getItems();

        foreach ($accounts as $account) {
            $webproperties = $this->analytics->management_webproperties->listManagementWebproperties($account->getId());
            foreach ($webproperties->getItems() as $property) {
                $data[] = array('propertyId' => $property->getId(), 'propertyName' => $property->getName(), 'accountId' => $account->getId());
            }
        }

        return $data;
    }

    /**
     * Constructs a Google API query and returns the result
     *
     * @param int timespan      Timespan for the data to query in days
     * @param int startOffset   An offset in days
     * @param string metrics    The needed metrics
     * @param array extra       Extra options suchs as dimentions, sort data, filter data,..
     *
     * @return GaData result    A data object containing the queried data
     */
    public function getResults($timespan, $startOffset, $metrics, $extra=array())
    {
        $profileId = $this->getProfileId();
        return $this->analytics->data_ga->get(
            'ga:' . $profileId,
            $timespan.'daysAgo',
            $startOffset.'daysAgo',
            $metrics,
            $extra
        );
    }

    /**
     * Constructs a Google API query and returns the result
     *
     * @param Date from         Start date for the data to query
     * @param Date to           End date in the past
     * @param string metrics    The needed metrics
     * @param array extra       Extra options suchs as dimentions, sort data, filter data,..
     *
     * @return GaData result    A data object containing the queried data
     */
    public function getResultsByDate($from, $to, $metrics, $extra = array())
    {
        $profileId = $this->getProfileId();
        return $this->analytics->data_ga->get(
            'ga:' . $profileId,
            $from,
            $to,
            $metrics,
            $extra
        );
    }

}
