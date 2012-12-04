<?php

namespace Kunstmaan\AdminBundle\Helper\Menu;

use Knp\Menu\Silex\RouterAwareFactory;
use Knp\Menu\ItemInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;


class MenuFactory extends RouterAwareFactory {

    protected $dispatcher;

    public function __construct(UrlGeneratorInterface $generator, EventDispatcherInterface $dispatcher)
    {
        parent::__construct($generator);
        $this->dispatcher = $dispatcher;
    }

    /**
     * Creates a MenuItem
     *
     * @param string $name
     * @param array  $options
     *
     * @return ItemInterface
     */
    public function createItem($name, array $options = array())
    {
        $item = new MenuItem($name, $this);
        $options = $this->buildOptions($options);
        $this->configureItem($item, $options);

        return $item;
    }

    /**
     * Configures the newly created item with the passed options
     *
     * @param ItemInterface $item
     * @param array         $options
     */
    protected function configureItem(ItemInterface $item, array $options)
    {
        parent::configureItem($item, $options);
        $item->setEventDispatcher($this->dispatcher);
    }

}