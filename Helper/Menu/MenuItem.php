<?php

namespace Kunstmaan\AdminBundle\Helper\Menu;

use Knp\Menu\MenuItem as KnpMenuItem;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Kunstmaan\AdminBundle\Event\Events;
use Kunstmaan\AdminBundle\Event\ConfigureMenuChildrenEvent;

class MenuItem extends KnpMenuItem
{

    /**
     * Boolean set to true when children of this MenuItem were requested and populated
     *
     * @var bool
     */
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

    /**
     * When populated is false, trigger the Event CONFIGURE_MENU_CHILDREN and
     * afterwards, set populated to true. The event will have to handle populating
     * the children array
     *
     * @return array|\Knp\Menu\ItemInterface[]
     */
    public function getChildren()
    {
        if (!$this->populated) {
            $this->dispatcher->dispatch(Events::CONFIGURE_MENU_CHILDREN, new ConfigureMenuChildrenEvent($this->factory, $this));
            $this->populated = true;
        }
        return $this->children;
    }

    /**
     * Return true when this MenuItem has children and at least one of them is displayed
     *
     * @return bool
     */
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