<?php

namespace Kunstmaan\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AnalyticsToken
 *
 * @ORM\Table(name="kuma_analytics_config")
 * @ORM\Entity(repositoryClass="Kunstmaan\AdminBundle\Repository\AnalyticsConfigRepository")
 */
class AnalyticsConfig extends \Kunstmaan\AdminBundle\Entity\AbstractEntity
{
    /**
     * @var string
     *
     * @ORM\Column(name="token", type="text", nullable=true)
     */
    private $token = null;

    /**
     * @var string
     *
     * @ORM\Column(name="property_id", type="string", nullable=true)
     */
    private $propertyId = null;
    /**
     * @var string
     *
     * @ORM\Column(name="account_id", type="string", nullable=true)
     */
    private $accountId = null;

    /**
     * Get token
     *
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set token
     *
     * @param string $token
     *
     * @return AnalyticsToken
     */
    public function setToken($token)
    {
        $this->token = $token;

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
     * Set propertyId
     *
     * @param string $propertyId
     *
     * @return AnalyticsProperty
     */
    public function setPropertyId($propertyId)
    {
        $this->propertyId = $propertyId;

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

    /**
     * Set accountId
     *
     * @param string $accountId
     *
     * @return AnalyticsProperty
     */
    public function setAccountId($accountId)
    {
        $this->accountId = $accountId;

        return $this;
    }
}
