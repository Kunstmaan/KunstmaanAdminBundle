<?php

namespace Kunstmaan\AdminBundle\Helper\Menu;

use Knp\Menu\Silex\RouterAwareFactory;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;


class MenuFactory extends RouterAwareFactory {

    protected $dispatcher;

    public function __construct(UrlGeneratorInterface $generator, EventDispatcherInterface $dispatcher)
    {
        parent::__construct($generator);
        $this->dispatcher = $dispatcher;
    }

    public function createItem($name, array $options = array(), $menuItemClass = null)
    {
        if(!is_null($menuItemClass)){
            $item = parent::createItem($name, $options, $menuItemClass);
        } else {
            $item = parent::createItem($name, $options, MenuBuilder::MENU_ITEM_CLASS);
        }

        $item->setEventDispatcher($this->dispatcher);

        return $item;
    }

}