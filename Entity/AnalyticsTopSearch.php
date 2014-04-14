<?php

namespace Kunstmaan\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AnalyticsTopSearches
 *
 * @ORM\Table(name="kuma_analytics_top_search")
 * @ORM\Entity(repositoryClass="Kunstmaan\AdminBundle\Repository\AnalyticsTopSearchesRepository")
 */
class AnalyticsTopSearch extends \Kunstmaan\AdminBundle\Entity\AbstractEntity
{
    /**
     * @ORM\ManyToOne(targetEntity="AnalyticsOverview", inversedBy="searches")
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
     * @return AnalyticsTopSearches
     */
    public function setOverview($overview)
    {
        $this->overview = $overview;

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
     * Set name
     *
     * @param string $name
     *
     * @return AnalyticsTopSearches
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get visits
     *
     * @return integer
     */
    public function getVisits()
    {
        return number_format($this->visits);
    }

    /**
     * Set visits
     *
     * @param integer $visits
     *
     * @return AnalyticsTopSearches
     */
    public function setVisits($visits)
    {
        $this->visits = $visits;

        return $this;
    }
}
