<?php

namespace Kunstmaan\AdminBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use Knp\Menu\ItemInterface;
use Knp\Menu\FactoryInterface;

class ConfigureTopMenuEvent extends Event
{
    /**
     * @var FactoryInterface
     */
    private $factory;

    /**
     * @var ItemInterface
     */
    private $menu;

    /**
     * @param FactoryInterface $factory           The factory
     * @param ItemInterface    $menu              The menu
     */
    public function __construct(FactoryInterface $factory, ItemInterface $menu)
    {
        $this->factory = $factory;
        $this->menu    = $menu;

    }

    /**
     * @return FactoryInterface
     */
    public function getFactory()
    {
        return $this->factory;
    }

    /**
     * @return ItemInterface
     */
    public function getMenu()
    {
        return $this->menu;
    }
}
