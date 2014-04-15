<?php

namespace Kunstmaan\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AnalyticsCampaign
 *
 * @ORM\Table(name="kuma_analytics_campaign")
 * @ORM\Entity(repositoryClass="Kunstmaan\AdminBundle\Repository\AnalyticsCampaignRepository")
 */
class AnalyticsCampaign extends \Kunstmaan\AdminBundle\Entity\AbstractEntity
{
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var integer
     *
     * @ORM\Column(name="visits", type="integer")
     */
    private $visits;

    /**
     * @ORM\ManyToOne(targetEntity="AnalyticsOverview", inversedBy="campaigns")
     * @ORM\JoinColumn(name="overview_id", referencedColumnName="id")
     */
    private $overview;


    /**
     * Set name
     *
     * @param string $name
     * @return AnalyticsCampaign
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set visits
     *
     * @param integer $visits
     * @return AnalyticsCampaign
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
     * Get overview
     *
     * @return integer
     */
    public function getOverview()
    {
        return $this->overview;
    }

    /**
     * Set overview
     *
     * @param integer $overview
     *
     * @return AnalyticsTopReferrals
     */
    public function setOverview($overview)
    {
        $this->overview = $overview;

        return $this;
    }
}
