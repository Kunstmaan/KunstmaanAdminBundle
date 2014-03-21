<?php

namespace Kunstmaan\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AnalyticsOverview
 *
 * @ORM\Table(name="kumaga_analytics_overview")
 * @ORM\Entity(repositoryClass="Kunstmaan\AdminBundle\Repository\AnalyticsOverviewRepository")
 */
class AnalyticsOverview extends \Kunstmaan\AdminBundle\Entity\AbstractEntity
{

    public function getTopReferrals() {
        return  $this->getTopReferralFirst() . ': ' . $this->getTopReferralFirstValue() . "\r\n" .
                $this->getTopReferralSecond() . ': ' . $this->getTopReferralSecondValue() . "\r\n" .
                $this->getTopReferralThird() . ': ' . $this->getTopReferralThirdValue() . "\r\n";
    }

    public function getTopSearches() {
        return  $this->getTopSearchFirst() . ': ' . $this->getTopSearchFirstValue() . "\r\n" .
                $this->getTopSearchSecond() . ': ' . $this->getTopSearchSecondValue() . "\r\n" .
                $this->getTopSearchThird() . ': ' . $this->getTopSearchThirdValue() . "\r\n";
    }

    public function getTrafficDirectPercentage() {
        return round(($this->trafficDirect / $this->visits) * 100);
    }

    public function getTrafficReferralPercentage() {
        return round(($this->trafficReferral / $this->visits) * 100);
    }

    public function getTrafficSearchEnginePercentage() {
        return round(($this->trafficSearchEngine / $this->visits) * 100);
    }

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var integer
     *
     * @ORM\Column(name="timespan", type="integer")
     */
    private $timespan;

    /**
     * @var integer
     *
     * @ORM\Column(name="start_days_ago", type="integer")
     */
    private $startOffset = 0;

    /**
     * @var integer
     *
     * @ORM\Column(name="visits", type="integer")
     */
    private $visits = 0;

    /**
     * @var integer
     *
     * @ORM\Column(name="returning_visits", type="integer")
     */
    private $returningVisits = 0;

    /**
     * @var integer
     *
     * @ORM\Column(name="new_visits", type="integer")
     */
    private $newVisits = 0;

    /**
     * @var integer
     *
     * @ORM\Column(name="pageviews", type="integer")
     */
    private $pageviews = 0;

    /**
     * @var integer
     *
     * @ORM\Column(name="traffic_direct", type="integer")
     */
    private $trafficDirect = 0;

    /**
     * @var integer
     *
     * @ORM\Column(name="traffic_referral", type="integer")
     */
    private $trafficReferral = 0;

    /**
     * @var integer
     *
     * @ORM\Column(name="traffic_search_engine", type="integer")
     */
    private $trafficSearchEngine = 0;


    /**
     * @var string
     *
     * @ORM\Column(name="top_referral_first", type="string")
     */
    private $topReferralFirst = '';
    /**
     * @var integer
     *
     * @ORM\Column(name="top_referral_first_value", type="integer")
     */
    private $topReferralFirstValue = 0;


    /**
     * @var string
     *
     * @ORM\Column(name="top_referral_second", type="string")
     */
    private $topReferralSecond = '';
    /**
     * @var integer
     *
     * @ORM\Column(name="top_referral_second_value", type="integer")
     */
    private $topReferralSecondValue = 0;


    /**
     * @var string
     *
     * @ORM\Column(name="top_referral_third", type="string")
     */
    private $topReferralThird = '';
    /**
     * @var integer
     *
     * @ORM\Column(name="top_referral_third_value", type="integer")
     */
    private $topReferralThirdValue = 0;




    /**
     * Set topReferralFirstValue
     *
     * @param string $topReferralFirstValue
     * @return AnalyticsOverview
     */
    public function setTopReferralFirstValue($topReferralFirstValue)
    {
        $this->topReferralFirstValue = $topReferralFirstValue;

        return $this;
    }

    /**
     * Get topReferralFirstValue
     *
     * @return string
     */
    public function getTopReferralFirstValue()
    {
        return $this->topReferralFirstValue;
    }



    /**
     * Set topReferralSecondValue
     *
     * @param string $topReferralSecondValue
     * @return AnalyticsOverview
     */
    public function setTopReferralSecondValue($topReferralSecondValue)
    {
        $this->topReferralSecondValue = $topReferralSecondValue;

        return $this;
    }

    /**
     * Get topReferralSecondValue
     *
     * @return string
     */
    public function getTopReferralSecondValue()
    {
        return $this->topReferralSecondValue;
    }


    /**
     * Set topReferralThirdValue
     *
     * @param string $topReferralThirdValue
     * @return AnalyticsOverview
     */
    public function setTopReferralThirdValue($topReferralThirdValue)
    {
        $this->topReferralThirdValue = $topReferralThirdValue;

        return $this;
    }

    /**
     * Get topReferralThirdValue
     *
     * @return string
     */
    public function getTopReferralThirdValue()
    {
        return $this->topReferralThirdValue;
    }


    /**
     * Set topReferralThird
     *
     * @param string $topReferralThird
     * @return AnalyticsOverview
     */
    public function setTopReferralThird($topReferralThird)
    {
        $this->topReferralThird = $topReferralThird;

        return $this;
    }

    /**
     * Get topReferralThird
     *
     * @return string
     */
    public function getTopReferralThird()
    {
        return $this->topReferralThird;
    }


    /**
     * Set topReferralSecond
     *
     * @param string $topReferralSecond
     * @return AnalyticsOverview
     */
    public function setTopReferralSecond($topReferralSecond)
    {
        $this->topReferralSecond = $topReferralSecond;

        return $this;
    }

    /**
     * Get topReferralSecond
     *
     * @return string
     */
    public function getTopReferralSecond()
    {
        return $this->topReferralSecond;
    }


    /**
     * Set topReferralFirst
     *
     * @param string $topReferralFirst
     * @return AnalyticsOverview
     */
    public function setTopReferralFirst($topReferralFirst)
    {
        $this->topReferralFirst = $topReferralFirst;

        return $this;
    }

    /**
     * Get topReferralFirst
     *
     * @return string
     */
    public function getTopReferralFirst()
    {
        return $this->topReferralFirst;
    }



    /**
     * Set trafficSearchEngine
     *
     * @param string $trafficSearchEngine
     * @return AnalyticsOverview
     */
    public function setTrafficSearchEngine($trafficSearchEngine)
    {
        $this->trafficSearchEngine = $trafficSearchEngine;

        return $this;
    }

    /**
     * Get trafficSearchEngine
     *
     * @return string
     */
    public function getTrafficSearchEngine()
    {
        return $this->trafficSearchEngine;
    }

    /**
     * Set trafficReferral
     *
     * @param string $trafficReferral
     * @return AnalyticsOverview
     */
    public function setTrafficReferral($trafficReferral)
    {
        $this->trafficReferral = $trafficReferral;

        return $this;
    }

    /**
     * Get trafficReferral
     *
     * @return string
     */
    public function getTrafficReferral()
    {
        return $this->trafficReferral;
    }

    /**
     * Set trafficDirect
     *
     * @param string $trafficDirect
     * @return AnalyticsOverview
     */
    public function setTrafficDirect($trafficDirect)
    {
        $this->trafficDirect = $trafficDirect;

        return $this;
    }

    /**
     * Get trafficDirect
     *
     * @return string
     */
    public function getTrafficDirect()
    {
        return $this->trafficDirect;
    }

    /**
     * Set newVisits
     *
     * @param string $newVisits
     * @return AnalyticsOverview
     */
    public function setNewVisits($newVisits)
    {
        $this->newVisits = $newVisits;

        return $this;
    }

    /**
     * Get newVisits
     *
     * @return string
     */
    public function getNewVisits()
    {
        return $this->newVisits;
    }


    /**
     * Set returningVisits
     *
     * @param string $returningVisits
     * @return AnalyticsOverview
     */
    public function setReturningVisits($returningVisits)
    {
        $this->returningVisits = $returningVisits;

        return $this;
    }

    /**
     * Get returningVisits
     *
     * @return string
     */
    public function getReturningVisits()
    {
        return $this->returningVisits;
    }


    /**
     * Set title
     *
     * @param string $title
     * @return AnalyticsOverview
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set startOffset
     *
     * @param integer $startOffset
     * @return AnalyticsOverview
     */
    public function setStartOffset($startOffset)
    {
        $this->startOffset = $startOffset;

        return $this;
    }

    /**
     * Get startOffset
     *
     * @return integer
     */
    public function getStartOffset()
    {
        return $this->startOffset;
    }

    /**
     * Set timespan
     *
     * @param integer $timespan
     * @return AnalyticsOverview
     */
    public function setTimespan($timespan)
    {
        $this->timespan = $timespan;

        return $this;
    }

    /**
     * Get timespan
     *
     * @return integer
     */
    public function getTimespan()
    {
        return $this->timespan;
    }

    /**
     * Set visits
     *
     * @param integer $visits
     * @return AnalyticsOverview
     */
    public function setVisits($visits)
    {
        $this->visits = $visits;

        return $this;
    }

    /**
     * Get visits
     *
     * @return integer
     */
    public function getVisits()
    {
        return $this->visits;
    }

    /**
     * Set pageviews
     *
     * @param integer $pageviews
     * @return AnalyticsOverview
     */
    public function setPageviews($pageviews)
    {
        $this->pageviews = $pageviews;

        return $this;
    }

    /**
     * Get pageviews
     *
     * @return integer
     */
    public function getPageviews()
    {
        return $this->pageviews;
    }

        /**
     * @var string
     *
     * @ORM\Column(name="top_Search_First", type="string")
     */
    private $topSearchFirst = '';
    /**
     * @var integer
     *
     * @ORM\Column(name="top_Search_First_value", type="integer")
     */
    private $topSearchFirstValue = 0;


    /**
     * Set topSearchFirst
     *
     * @param string $topSearchFirst
     * @return AnalyticsOverview
     */
    public function setTopSearchFirst($topSearchFirst)
    {
        $this->topSearchFirst = $topSearchFirst;

        return $this;
    }

    /**
     * Get topSearchFirst
     *
     * @return string
     */
    public function getTopSearchFirst()
    {
        return $this->topSearchFirst;
    }

    /**
     * Set topSearchFirstValue
     *
     * @param string $topSearchFirstValue
     * @return AnalyticsOverview
     */
    public function setTopSearchFirstValue($topSearchFirstValue)
    {
        $this->topSearchFirstValue = $topSearchFirstValue;

        return $this;
    }

    /**
     * Get topSearchFirstValue
     *
     * @return string
     */
    public function getTopSearchFirstValue()
    {
        return $this->topSearchFirstValue;
    }

    /**
     * @var string
     *
     * @ORM\Column(name="top_Search_Second", type="string")
     */
    private $topSearchSecond = '';
    /**
     * @var integer
     *
     * @ORM\Column(name="top_Search_Second_value", type="integer")
     */
    private $topSearchSecondValue = 0;


    /**
     * Set topSearchSecond
     *
     * @param string $topSearchSecond
     * @return AnalyticsOverview
     */
    public function setTopSearchSecond($topSearchSecond)
    {
        $this->topSearchSecond = $topSearchSecond;

        return $this;
    }

    /**
     * Get topSearchSecond
     *
     * @return string
     */
    public function getTopSearchSecond()
    {
        return $this->topSearchSecond;
    }

    /**
     * Set topSearchSecondValue
     *
     * @param string $topSearchSecondValue
     * @return AnalyticsOverview
     */
    public function setTopSearchSecondValue($topSearchSecondValue)
    {
        $this->topSearchSecondValue = $topSearchSecondValue;

        return $this;
    }

    /**
     * Get topSearchSecondValue
     *
     * @return string
     */
    public function getTopSearchSecondValue()
    {
        return $this->topSearchSecondValue;
    }

        /**
     * @var string
     *
     * @ORM\Column(name="top_Search_Third", type="string")
     */
    private $topSearchThird = '';
    /**
     * @var integer
     *
     * @ORM\Column(name="top_Search_Third_value", type="integer")
     */
    private $topSearchThirdValue = 0;


    /**
     * Set topSearchThird
     *
     * @param string $topSearchThird
     * @return AnalyticsOverview
     */
    public function setTopSearchThird($topSearchThird)
    {
        $this->topSearchThird = $topSearchThird;

        return $this;
    }

    /**
     * Get topSearchThird
     *
     * @return string
     */
    public function getTopSearchThird()
    {
        return $this->topSearchThird;
    }

    /**
     * Set topSearchThirdValue
     *
     * @param string $topSearchThirdValue
     * @return AnalyticsOverview
     */
    public function setTopSearchThirdValue($topSearchThirdValue)
    {
        $this->topSearchThirdValue = $topSearchThirdValue;

        return $this;
    }

    /**
     * Get topSearchThirdValue
     *
     * @return string
     */
    public function getTopSearchThirdValue()
    {
        return $this->topSearchThirdValue;
    }

}
