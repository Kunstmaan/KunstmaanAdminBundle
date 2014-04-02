<?php

namespace Kunstmaan\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AnalyticsToken
 *
 * @ORM\Table(name="kuma_analytics_token")
 * @ORM\Entity
 */
class AnalyticsToken extends \Kunstmaan\AdminBundle\Entity\AbstractEntity
{
    /**
     * @var string
     *
     * @ORM\Column(name="token", type="text")
     */
    private $token;

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
}
