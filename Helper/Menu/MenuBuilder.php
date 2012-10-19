<?php

namespace Kunstmaan\AdminBundle\Helper\Menu;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Knp\Menu\Silex\RouterAwareFactory;
use Kunstmaan\AdminBundle\Event\Events;
use Kunstmaan\AdminBundle\Event\ConfigureMenuEvent;
use Knp\Menu\FactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Kunstmaan\AdminBundle\Helper\Menu\MenuItem;

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

    private $router;

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
     * @param Request $request
     *
     * @return ItemInterface
     */
    public function createMenu(Request $request)
    {
        $router = $this->router;
        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttribute('class', 'nav');

        $menu->addChild($this->factory->createItem('Settings', array('route' => 'KunstmaanAdminBundle_settings')));
        $menu->addChild($this->factory->createItem('Modules', array('route' => 'KunstmaanAdminBundle_modules')));

        $this->dispatcher->dispatch(Events::CONFIGURE_MENU, new ConfigureMenuEvent($this->factory, $menu));

        return $menu;
    }

}