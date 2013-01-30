<?php

namespace Kunstmaan\AdminBundle\Twig;

use ArrayIterator;
use RecursiveIteratorIterator;
use Knp\Menu\ItemInterface;
use Knp\Menu\Iterator\CurrentItemFilterIterator;
use Knp\Menu\Iterator\RecursiveItemIterator;
use Knp\Menu\Matcher\Matcher;

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
            'admin_menu_top'  => new \Twig_Function_Method($this, 'getTopMenuItem'),
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

        if (!empty($array)) {
            return $array[0];
        }

        return null;
    }

    /**
     * Returns the top MenuItem from given Menu
     *
     * @param ItemInterface $menu
     * @param int $level
     *
     * @return string
     */
    public function getTopMenuItem(ItemInterface $menu, $level)
    {
        $item = $this->getCurrentMenuItem($menu);
        if (isset($item)) {
            $breadcrumbs = $item->getBreadcrumbsArray();
            return $breadcrumbs[$level]['item'];
        }
        return null;
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
