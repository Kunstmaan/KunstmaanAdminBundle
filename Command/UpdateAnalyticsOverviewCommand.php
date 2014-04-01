<?php

namespace Kunstmaan\AdminBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Kunstmaan\AdminBundle\Entity\GoogleClientHelper;
use Kunstmaan\AdminBundle\Entity\GoogleAnalyticsHelper;
use Kunstmaan\AdminBundle\Entity\AnalyticsTopReferral;

/**
 * Symfony CLI command to update the analytics data using app/console kuma:ga:update
 */
class UpdateAnalyticsOverviewCommand extends ContainerAwareCommand
{

    /** @var GoogleClientHelper $googleClientHelper */
    private $googleClientHelper;
    /** @var Client $googleClient */
    private $googleClient;
    /** @var GoogleAnalyticsHelper $analyticsHelper */
    private $analyticsHelper;
    /** @var EntityManager $em */
    private $em;
    /** @var OutputInterface $output */
    private $output;

    /**
     * Configures the current command.
     */
    protected function configure()
    {
        $this
            ->setName('kuma:ga:update')
            ->setDescription('Update Google Analytics overviews')
        ;
    }

    /**
     * Inits instance variables for global usage.
     *
     * @param OutputInterface $output The output
     */
    private function init($output)
    {
        $this->output = $output;

        // get API client
        $this->googleClientHelper = $this->getContainer()->get('kunstmaan_admin.googleclienthelper');

        // setup entity manager
        $this->em = $this->getContainer()->get('doctrine')->getEntityManager();
    }

    /**
     * Executes the current command.
     *
     * @param InputInterface  $input  The input
     * @param OutputInterface $output The output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->init($output);
        if ($this->googleClientHelper->tokenIsSet()) {
            // create API Analytics helper to execute queries
            $this->analyticsHelper = $this->getContainer()->get('kunstmaan_admin.googleanalyticshelper');
            $this->analyticsHelper->init($this->googleClientHelper);

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

    /**
     * Fetch data for daily overviews
     */
    private function getDaily()
    {
        $this->output->writeln('Fetching daily visits');
        $dailyOverview = $this->em->getRepository('KunstmaanAdminBundle:AnalyticsDailyOverview')->getOverview();
        $data = array();

            // Fetching daily data for 3 months.
            $results = $this->analyticsHelper->getResults(93, 0, 'ga:visits', array('dimensions' => 'ga:date', 'sort' => '-ga:date'));
            $rows = $results->getRows();
            foreach ($rows as $row) {
                $date = substr($row[0], 0, 4) . '-' . substr($row[0], 4, 2) . '-' . substr($row[0], 6, 2);
                $data[] = array('key' => $date, 'data' => $row[1]);
            }

            // adding data to the AnalyticsDailyOverview object
            $dailyOverview->setData(json_encode($data, JSON_UNESCAPED_SLASHES));

            // save dailyOverview to DB
            $this->output->writeln("\t" . 'Persisting..');
            $this->em->persist($dailyOverview);
            $this->em->flush();
    }

    /**
     * Fetch normal metric data and set it for the overview
     *
     * @param AnalyticsOverview $overview The overview
     */
    private function getMetrics(&$overview)
    {
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

    /**
     * Fetch day-specific data and set it for the overview
     *
     * @param AnalyticsOverview $overview The overview
     */
    private function getDayData(&$overview)
    {
        $this->output->writeln("\t" . 'Fetching day-specific data..');
        $results = $this->analyticsHelper->getResults($overview->getTimespan(), $overview->getStartOffset(), 'ga:visits', array('dimensions' => 'ga:hour'));
        $rows = $results->getRows();

        $data = array();
        foreach ($rows as $row) {
            $data[] = array('key' => $row[0].'h', 'data' => $row[1]);
        }

        // adding data to the AnalyticsDailyOverview object
        $overview->setDayData(json_encode($data, JSON_UNESCAPED_SLASHES));
    }

    /**
     * Fetch visitor type data and set it for the overview
     *
     * @param AnalyticsOverview $overview The overview
     */
    private function getVisitorTypes(&$overview)
    {
         // visitor types
        $this->output->writeln("\t" . 'Fetching visitor types..');
        $results = $this->analyticsHelper->getResults($overview->getTimespan(), $overview->getStartOffset(), 'ga:visits', array('dimensions' => 'ga:visitorType'));
        $rows = $results->getRows();

            // new visitors
            $data = is_array($rows) && isset($rows[0][1]) ? $rows[0][1] : 0;
            $overview->setNewVisits($data);

            // returning visitors
            $data = is_array($rows) && isset($rows[1][1]) ? $rows[1][1] : 0;
            $overview->setReturningVisits($data);
    }

    /**
     * Fetch traffic source data and set it for the overview
     *
     * @param AnalyticsOverview $overview The overview
     */
    private function getTrafficSources(&$overview)
    {
        // traffic sources
        $this->output->writeln("\t" . 'Fetching traffic sources..');
        $results = $this->analyticsHelper->getResults($overview->getTimespan(), $overview->getStartOffset(), 'ga:visits', array('dimensions' => 'ga:medium', 'sort' => 'ga:medium'));
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

    /**
     * Fetch referral data and set it for the overview
     *
     * @param AnalyticsOverview $overview The overview
     */
    private function getTopReferrals(&$overview)
    {
        // top referral sites
        $this->output->writeln("\t" . 'Fetching referral sites..');
        $results = $this->analyticsHelper->getResults($overview->getTimespan(), $overview->getStartOffset(), 'ga:visits', array('dimensions' => 'ga:source', 'sort' => '-ga:visits', 'filters' => 'ga:medium==referral', 'max-results' => '3'));
        $rows = $results->getRows();

            // delete existing entries
            if (is_array($overview->getReferrals()->toArray())) {
                foreach ($overview->getReferrals()->toArray() as $referral) {
                    $this->em->remove($referral);
                }
                $this->em->flush();
            }

            // load new referrals
            if (is_array($rows)) {
                foreach ($rows as $key=>$row) {
                    $referral = new AnalyticsTopReferral();
                    $referral->setName($row[0]);
                    $referral->setVisits($row[1]);
                    $referral->setOverview($overview);
                    $overview->getReferrals()->add($referral);
                }
            }
    }

    /**
     * Fetch search terms data and set it for the overview
     *
     * @param AnalyticsOverview $overview The overview
     */
    private function getTopSearches(&$overview)
    {
        // top searches
        $this->output->writeln("\t" . 'Fetching searches..');
        $results = $this->analyticsHelper->getResults($overview->getTimespan(), $overview->getStartOffset(), 'ga:searchUniques', array('dimensions' => 'ga:searchKeyword', 'sort' => '-ga:searchUniques', 'max-results' => '3'));
        $rows = $results->getRows();

            // delete existing entries
            if (is_array($overview->getSearches()->toArray())) {
                foreach ($overview->getSearches()->toArray() as $search) {
                    $this->em->remove($search);
                }
                $this->em->flush();
            }

            // load new searches
            if (is_array($rows)) {
                foreach ($rows as $key=>$row) {
                    $search = new AnalyticsTopSearch();
                    $search->setName($row[0]);
                    $search->setVisits($row[1]);
                    $search->setOverview($overview);
                    $overview->getSearches()->add($search);
                }
            }

    }

    /**
     * Reset the data for the overview
     *
     * @param AnalyticsOverview $overview The overview
     */
    private function reset(&$overview)
    {
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
