<?php

namespace Kunstmaan\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AnalyticsProperty
 *
 * @ORM\Table(name="kumaga_analytics_property")
 * @ORM\Entity(repositoryClass="Kunstmaan\AdminBundle\Repository\AnalyticsPropertyRepository")
 */
class AnalyticsProperty extends \Kunstmaan\AdminBundle\Entity\AbstractEntity
{
    /**
     * @var string
     *
     * @ORM\Column(name="property_id", type="string", nullable=true)
     */
    private $propertyId = null;


    /**
     * Set propertyId
     *
     * @param string $propertyId
     * @return AnalyticsProperty
     */
    public function setPropertyId($propertyId)
    {
        $this->propertyId = $propertyId;

        return $this;
    }

    /**
     * Get propertyId
     *
     * @return string
     */
    public function getPropertyId()
    {
        return $this->propertyId;
    }
    /**
     * @var string
     *
     * @ORM\Column(name="account_id", type="string", nullable=true)
     */
    private $accountId = null;


    /**
     * Set accountId
     *
     * @param string $accountId
     * @return AnalyticsProperty
     */
    public function setAccountId($accountId)
    {
        $this->accountId = $accountId;

        return $this;
    }

    /**
     * Get accountId
     *
     * @return string
     */
    public function getAccountId()
    {
        return $this->accountId;
    }
}
