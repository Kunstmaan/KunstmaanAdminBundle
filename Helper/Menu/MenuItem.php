<?php

namespace Kunstmaan\AdminBundle\Helper\Menu;

use Knp\Menu\MenuItem as KnpMenuItem;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Kunstmaan\AdminBundle\Event\Events;
use Kunstmaan\AdminBundle\Event\ConfigureMenuChildrenEvent;

class MenuItem extends KnpMenuItem
{

    private $populated = false;

    /**
     * @var EventDispatcherInterface
     */
    protected $dispatcher;

    public function getEventDispatcher()
    {
        return $this->dispatcher;
    }

    /**
     * @param EventDispatcherInterface $dispatcher
     */
    public function setEventDispatcher(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    public function getChildren()
    {
        if (!$this->populated) {
            $this->dispatcher->dispatch(Events::CONFIGURE_MENU_CHILDREN, new ConfigureMenuChildrenEvent($this->factory, $this));
            $this->populated = true;
        }
        return $this->children;
    }

    public function hasChildren()
    {
        foreach ($this->getChildren() as $child) {
            if ($child->isDisplayed()) {
                return true;
            }
        }

        return false;
    }

}