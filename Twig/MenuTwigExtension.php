<?php

namespace Kunstmaan\AdminBundle\Twig;

use ArrayIterator;
use Knp\Menu\ItemInterface;
use Knp\Menu\Iterator\CurrentItemFilterIterator;
use Knp\Menu\Iterator\RecursiveItemIterator;
use Knp\Menu\Matcher\Matcher;
use RecursiveIteratorIterator;

/**
 * MenuTwigExtension
 */
class MenuTwigExtension extends \Twig_Extension
{
    private $matcher;

    public function __construct(Matcher $matcher)
    {
        $this->matcher = $matcher;
    }

    /**
     * Get Twig functions defined in this extension.
     *
     * @return array
     */
    public function getFunctions()
    {
        return array(
            'admin_menu_current'  => new \Twig_Function_Method($this, 'getCurrentMenuItem'),
        );
    }

    /**
     * Returns the current ItemMenu from given Menu
     * @param ItemInterface $menu
     *
     * @return string
     */
    public function getCurrentMenuItem(ItemInterface $menu)
    {
        $treeIterator = new RecursiveIteratorIterator(
            new RecursiveItemIterator(
                new ArrayIterator(array($menu))
            ),
            RecursiveIteratorIterator::CHILD_FIRST
        );

        $iterator = new CurrentItemFilterIterator($treeIterator, $this->matcher);

        $array = array();
        foreach ($iterator as $item) {
            $array[] = $item;
        }

        return $array[0];
    }

    /**
     * Get the Twig extension name.
     *
     * @return string
     */
    public function getName()
    {
        return 'adminmenu_twig_extension';
    }
}
