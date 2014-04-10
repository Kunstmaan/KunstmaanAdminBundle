<?php

namespace Kunstmaan\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AnalyticsDailyOverview
 *
 * @ORM\Table(name="kuma_analytics_daily_overview")
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="Kunstmaan\AdminBundle\Repository\AnalyticsDailyOverviewRepository")
 */
class AnalyticsDailyOverview extends \Kunstmaan\AdminBundle\Entity\AbstractEntity
{
    /**
     * @var array
     *
     * @ORM\Column(name="data", type="text")
     */
    private $data = '';

    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Set data
     *
     * @param array $data
     *
     * @return AnalyticsDailyOverview
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }
}
