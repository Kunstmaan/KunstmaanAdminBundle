<?php

namespace Kunstmaan\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AnalyticsTopReferrals
 *
 * @ORM\Table(name="kumaga_analytics_top_referral")
 * @ORM\Entity(repositoryClass="Kunstmaan\AdminBundle\Repository\AnalyticsTopReferralsRepository")
 */
class AnalyticsTopReferral extends \Kunstmaan\AdminBundle\Entity\AbstractEntity
{
    /**
     * @ORM\ManyToOne(targetEntity="AnalyticsOverview", inversedBy="referrals")
     * @ORM\JoinColumn(name="overview_id", referencedColumnName="id")
     */
    private $overview;

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
     * Set overview
     *
     * @param integer $overview
     * @return AnalyticsTopReferrals
     */
    public function setOverview($overview)
    {
        $this->overview = $overview;

        return $this;
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
     * Set name
     *
     * @param string $name
     * @return AnalyticsTopReferrals
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
     * @return AnalyticsTopReferrals
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
}
