<?php

namespace Kunstmaan\AdminBundle\Entity;

use GoogleApi\Client;
use GoogleApi\Contrib\apiAnalyticsService;

/**
 * This helper will setup a google analytics object
 */
class GoogleAnalyticsHelper
{

    private $analytics;
    private $clientHelper;

    public function __construct(Client $googleClient, $clientHelper) {
        $this->analytics = new apiAnalyticsService($googleClient);
        $this->clientHelper = $clientHelper;
    }


    public function getFirstProfileId() {
        $accounts = $this->analytics->management_accounts->listManagementAccounts();
        if (count($accounts->getItems()) > 0) {
            $items = $accounts->getItems();
            $firstAccountId = $items[0]->getId();

            $webproperties = $this->analytics->management_webproperties->listManagementWebproperties($firstAccountId);

            if (count($webproperties->getItems()) > 0) {
                $items = $webproperties->getItems();
                //$firstWebpropertyId = $items[$this->clientHelper->getPropertyId()]->getId();

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

    public function getProperties() {
        $data = [];
        $accounts = $this->analytics->management_accounts->listManagementAccounts()->getItems();


        foreach ($accounts as $account) {
            $webproperties = $this->analytics->management_webproperties->listManagementWebproperties($account->getId());
            foreach ($webproperties->getItems() as $property) {
                $data[] = ['propertyId' => $property->getId(), 'propertyName' => $property->getName(), 'accountId' => $account->getId()];
            }
        }

        return $data;
    }

    public function getResults($timespan, $startOffset, $metrics, $extra=[]) {
        $profileId = $this->getFirstProfileId();
         return $this->analytics->data_ga->get(
                 'ga:' . $profileId,
                 $timespan.'daysAgo',
                 $startOffset.'daysAgo',
                 $metrics,
                 $extra
            );
    }

    public function getResultsByDate($from, $to, $metrics, $extra = []) {
        $profileId = $this->getFirstProfileId();
        return $this->analytics->data_ga->get(
            'ga:' . $profileId,
            $from,
            $to,
            $metrics,
            $extra
        );
    }

    private function printResults(&$results) {
        echo "<pre>";
        print_r($results);
        echo "</pre>";


        if (count($results->getRows()) > 0) {
            $profileName = $results->getProfileInfo()->getProfileName();
            $rows = $results->getRows();
            $visits = $rows[0][0];

            print "<p>First view (profile) found: $profileName</p>";
            print "<p>Total visits: $visits</p>";

        } else {
            print '<p>No results found.</p>';
        }
    }

}
