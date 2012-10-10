<?php

namespace Kunstmaan\AdminBundle\Helper\Menu;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Kunstmaan\AdminBundle\Event\Events;
use Kunstmaan\AdminBundle\Event\ConfigureTopMenuEvent;
use Knp\Menu\FactoryInterface;
use Symfony\Component\HttpFoundation\Request;

class MenuBuilder
{

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
     * @param Request $request
     *
     * @return ItemInterface
     */
    public function createTopMenu(Request $request)
    {
        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttribute('class', 'nav');

        $menu->addChild('Settings', array('route' => 'KunstmaanAdminBundle_settings'));
        $menu->addChild('Modules', array('route' => 'KunstmaanAdminBundle_modules'));

        $this->dispatcher->dispatch(Events::CONFIGURE_TOP_MENU, new ConfigureTopMenuEvent($this->factory, $menu));

        return $menu;
    }

}