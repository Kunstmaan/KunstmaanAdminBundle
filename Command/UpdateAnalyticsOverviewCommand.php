<?php

namespace Kunstmaan\AdminBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Kunstmaan\AdminBundle\Entity\GoogleClientHelper;
use Kunstmaan\AdminBundle\Entity\GoogleAnalyticsHelper;


class UpdateAnalyticsOverviewCommand extends ContainerAwareCommand {

    private $googleClientHelper;
    private $googleClient;
    private $analyticsHelper;
    private $em;
    private $output;


    protected function configure()
    {
        $this
            ->setName('kuma:ga:update')
            ->setDescription('Update Google Analytics overviews')
        ;
    }

    private function init($output) {
        $this->output = $output;

        // get API client credentials
        $clientId       = $this->getContainer()->getParameter('google.api.client_id');
        $clientSecret   = $this->getContainer()->getParameter('google.api.client_secret');
        $redirectUri    = $this->getContainer()->getParameter('google.api.redirect_uri');
        $devKey         = $this->getContainer()->getParameter('google.api.dev_key');

        // create API client
        $this->googleClientHelper = new GoogleClientHelper($clientId, $clientSecret, $redirectUri, $devKey, $this->getContainer()->get('doctrine')->getEntityManager());
        $this->googleClient = $this->googleClientHelper->getClient();

        // setup entity manager
        $this->em = $this->getContainer()->get('doctrine')->getEntityManager();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->init($output);
        if ($this->googleClientHelper->tokenIsSet()) {
            // create API Analytics helper to execute queries
            $this->analyticsHelper = new GoogleAnalyticsHelper($this->googleClient, $this->googleClientHelper);

            // daily data for 3 months
            $this->getDaily();

            // get data for each overview
            $overviews = $this->em->getRepository('KunstmaanAdminBundle:AnalyticsOverview')->getAll();
            foreach ($overviews as $overview) {
                $this->output->writeln('Getting data for overview "' .$overview->getTitle(). '"');

                // metric data
                $this->getMetrics($overview);

                // day-specific data
                if ($overview->getUseDayData()) {
                    $this->getDayData($overview);
                }

                if ($overview->getVisits()) { // if there are any visits
                    // visitor types
                    $this->getVisitorTypes($overview);

                    // traffic sources
                    $this->getTrafficSources($overview);

                    // top referrals
                    $this->getTopReferrals($overview);

                    // top searches
                    $this->getTopSearches($overview);
                } else { // if no visits
                    // reset overview
                    $this->reset($overview);
                    $this->output->writeln("\t" . 'No visitors');
                }
                // persist entity back to DB
                $this->output->writeln("\t" . 'Persisting..');
                $this->em->persist($overview);
                $this->em->flush();
            }
            $this->output->writeln('Google Analytics data succesfully updated'); // done
        } else {
            $this->output->writeln('You haven\'t configured a Google account yet, or the token is invalid'); // error
        }

    }

    private function getDaily() {
        // get data for the daily overviews
        $this->output->writeln('Fetching daily visits');
        $dailyOverview = $this->em->getRepository('KunstmaanAdminBundle:AnalyticsDailyOverview')->getOverview();
        $data = [];

            // Fetching daily data for 3 months.
            $results = $this->analyticsHelper->getResults(93, 0, 'ga:visits', ['dimensions' => 'ga:date', 'sort' => '-ga:date']);
            $rows = $results->getRows();
            foreach ($rows as $row) {
                $date = substr($row[0], 0, 4) . '-' . substr($row[0], 4, 2) . '-' . substr($row[0], 6, 2);
                $data[] = ['key' => $date, 'data' => $row[1]];
            }

            // adding data to the AnalyticsDailyOverview object
            $dailyOverview->setData(json_encode($data, JSON_UNESCAPED_SLASHES));

            // save dailyOverview to DB
            $this->output->writeln("\t" . 'Persisting..');
            $this->em->persist($dailyOverview);
            $this->em->flush();
    }

    private function getMetrics(&$overview) {
        // normal metrics
        $this->output->writeln("\t" . 'Fetching metrics..');

            // visits metric
            $results = $this->analyticsHelper->getResults($overview->getTimespan(), $overview->getStartOffset(), 'ga:visits');
            $rows = $results->getRows();
            $visits = is_numeric($rows[0][0]) ? $rows[0][0] : 0;
            $overview->setVisits($visits);

            // pageviews metric
            $results = $this->analyticsHelper->getResults($overview->getTimespan(), $overview->getStartOffset(), 'ga:pageviews');
            $rows = $results->getRows();
            $pageviews = is_numeric($rows[0][0]) ? $rows[0][0] : 0;
            $overview->setPageViews($pageviews);
    }

    private function getDayData(&$overview) {
        // fetching hourly data
        $this->output->writeln("\t" . 'Fetching day-specific data..');
        $results = $this->analyticsHelper->getResults($overview->getTimespan(), $overview->getStartOffset(), 'ga:visits', ['dimensions' => 'ga:hour']);
        $rows = $results->getRows();

        $data = [];
        foreach ($rows as $row) {
            $data[] = ['key' => $row[0].'h', 'data' => $row[1]];
        }

        // adding data to the AnalyticsDailyOverview object
        $overview->setDayData(json_encode($data, JSON_UNESCAPED_SLASHES));
    }

    private function getVisitorTypes(&$overview) {
         // visitor types
        $this->output->writeln("\t" . 'Fetching visitor types..');
        $results = $this->analyticsHelper->getResults($overview->getTimespan(), $overview->getStartOffset(), 'ga:visits', ['dimensions' => 'ga:visitorType']);
        $rows = $results->getRows();

            // new visitors
            $data = is_array($rows) && isset($rows[0][1]) ? $rows[0][1] : 0;
            $overview->setNewVisits($data);

            // returning visitors
            $data = is_array($rows) && isset($rows[1][1]) ? $rows[1][1] : 0;
            $overview->setReturningVisits($data);
    }

    private function getTrafficSources(&$overview) {
        // traffic sources
        $this->output->writeln("\t" . 'Fetching traffic sources..');
        $results = $this->analyticsHelper->getResults($overview->getTimespan(), $overview->getStartOffset(), 'ga:visits', ['dimensions' => 'ga:medium', 'sort' => 'ga:medium']);
        $rows = $results->getRows();

        // resetting default values
        $overview->setTrafficDirect(0);
        $overview->setTrafficSearchEngine(0);
        $overview->setTrafficReferral(0);

        if (is_array($rows)) {
            foreach($rows as $row) {
                switch ($row[0]) {

                    case '(none)': // direct traffic
                        $overview->setTrafficDirect($row[1]);
                        break;

                    case 'organic': // search engine traffic
                        $overview->setTrafficSearchEngine($row[1]);
                        break;

                    case 'referral': // referral traffic
                        $overview->setTrafficReferral($row[1]);
                        break;

                    default: // TODO other referral types? https://developers.google.com/analytics/devguides/reporting/core/dimsmets#view=detail&group=traffic_sources&jump=ga_medium
                        break;
                }
            }
        }
    }

    private function getTopReferrals(&$overview) {
        // top referral sites
        $this->output->writeln("\t" . 'Fetching referral sites..');
        $results = $this->analyticsHelper->getResults($overview->getTimespan(), $overview->getStartOffset(), 'ga:visits', ['dimensions' => 'ga:source', 'sort' => '-ga:visits', 'filters' => 'ga:medium==referral']);
        $rows = $results->getRows();

            // #1 referral
            $overview->setTopReferralFirst(isset($rows[0][0]) ? $rows[0][0] : '');
            $overview->setTopReferralFirstValue(isset($rows[0][1]) ? $rows[0][1] : 0);

            // #2 referral
            $overview->setTopReferralSecond(isset($rows[1][0]) ? $rows[1][0] : '');
            $overview->setTopReferralSecondValue(isset($rows[1][1]) ? $rows[1][1] : 0);

            // #3 referral
            $overview->setTopReferralThird(isset($rows[2][0]) ? $rows[2][0] : '');
            $overview->setTopReferralThirdValue(isset($rows[2][1]) ? $rows[2][1] : 0);
    }

    private function getTopSearches(&$overview) {
        // top searches
        $this->output->writeln("\t" . 'Fetching searches..');
        $results = $this->analyticsHelper->getResults($overview->getTimespan(), $overview->getStartOffset(), 'ga:searchUniques', ['dimensions' => 'ga:searchKeyword', 'sort' => '-ga:searchUniques']);
        $rows = $results->getRows();

            // #1 search
            $overview->setTopSearchFirst(isset($rows[0][0]) ? $rows[0][0] : '');
            $overview->setTopSearchFirstValue(isset($rows[0][1]) ? $rows[0][1] : 0);

            // #2 search
            $overview->setTopSearchSecond(isset($rows[1][0]) ? $rows[1][0] : '');
            $overview->setTopSearchSecondValue(isset($rows[1][1]) ? $rows[1][1] : 0);

            // #3 search
            $overview->setTopSearchThird(isset($rows[2][0]) ? $rows[2][0] : '');
            $overview->setTopSearchThirdValue(isset($rows[2][1]) ? $rows[2][1] : 0);
    }

    private function reset(&$overview) {
        // reset overview
        $overview->setNewVisits(0);
        $overview->setReturningVisits(0);
        $overview->setTrafficDirect(0);
        $overview->setTrafficSearchEngine(0);
        $overview->setTrafficReferral(0);
        $overview->setTopReferralFirst('');
        $overview->setTopReferralSecond('');
        $overview->setTopReferralThird('');
        $overview->setTopReferralFirstValue(0);
        $overview->setTopReferralSecondValue(0);
        $overview->setTopReferralThirdValue(0);
        $overview->setTopSearchFirst('');
        $overview->setTopSearchSecond('');
        $overview->setTopSearchThird('');
        $overview->setTopSearchFirstValue(0);
        $overview->setTopSearchSecondValue(0);
        $overview->setTopSearchThirdValue(0);
    }


}
