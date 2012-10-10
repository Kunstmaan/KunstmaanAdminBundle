<?php

namespace Kunstmaan\AdminBundle\Helper\Menu;

use Knp\Menu\Silex\Voter\RouteVoter as BaseRouteVoter;

class RouteVoter extends BaseRouteVoter
{
    public function __construct($container)
    {
        $this->setRequest($container->get('request'));
    }
}