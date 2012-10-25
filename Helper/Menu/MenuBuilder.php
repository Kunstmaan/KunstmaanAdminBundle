<?php

namespace Kunstmaan\AdminBundle\Helper\Menu;

use Kunstmaan\AdminBundle\Event\Events;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Kunstmaan\AdminBundle\Event\ConfigureMenuEvent;
use Knp\Menu\FactoryInterface;

class MenuBuilder
{

    const MENU_ITEM_CLASS = 'Kunstmaan\AdminBundle\Helper\Menu\MenuItem';

    /**
     * @var FactoryInterface
     */
    private $factory;

    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    /**
     * @param FactoryInterface $factory
     * @param EventDispatcherInterface $dispatcher The event dispatcher
     */
    public function __construct(FactoryInterface $factory, EventDispatcherInterface $dispatcher)
    {
        $this->factory = $factory;
        $this->dispatcher = $dispatcher;
    }

    /**
     * Create the menu
     * Triggers the event CONFIGURE_MENU to enable listeners to edit and append the menu
     *
     * @return ItemInterface
     */
    public function createMenu()
    {
        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttribute('class', 'nav');

        $settingsMenu = $menu->addChild($this->factory->createItem('Settings', array('route' => 'KunstmaanAdminBundle_settings')));
        $settingsMenu->setAttribute('rel', 'Settings');
        $modulesMenu = $menu->addChild($this->factory->createItem('Modules', array('route' => 'KunstmaanAdminBundle_modules')));
        $modulesMenu->setAttribute('rel', 'Modules');

        $this->dispatcher->dispatch(Events::CONFIGURE_MENU, new ConfigureMenuEvent($this->factory, $menu));

        return $menu;
    }

}