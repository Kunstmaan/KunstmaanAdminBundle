<?php

namespace Kunstmaan\AdminBundle\Helper\Menu;

use Kunstmaan\AdminBundle\Helper\Menu\MenuAdaptorInterface;
use Kunstmaan\AdminBundle\Helper\Menu\MenuBuilder;
use Kunstmaan\AdminBundle\Helper\Menu\MenuItem;

use Symfony\Component\HttpFoundation\Request;

/**
 * MenuAdaptor to add the Modules MenuItem to the top menu
 */
class ModulesMenuAdaptor implements MenuAdaptorInterface
{
    /**
     * In this method you can add children for a specific parent, but also remove and change the already created children
     *
     * @param MenuBuilder $menu      The MenuBuilder
     * @param MenuItem[]  &$children The current children
     * @param MenuItem    $parent    The parent Menu item
     * @param Request     $request   The Request
     */
    public function adaptChildren(MenuBuilder $menu, array &$children, MenuItem $parent = null, Request $request = null)
    {
        if (is_null($parent)) {
            $menuItem = new TopMenuItem($menu);
            $menuItem->setRoute('KunstmaanAdminBundle_modules')
                ->setInternalName("Modules")
                ->setParent($parent);
            if (stripos($request->attributes->get('_route'), $menuItem->getRoute()) === 0) {
                $menuItem->setActive(true);
            }

            $subMenuItem = new TopMenuItem($menu);
            $subMenuItem->setRoute('kunstmaanadminbundle_admin_analyticsoverview');
            $subMenuItem->setInternalName('Analytics Overviews');
            $subMenuItem->setParent($menuItem);
            if (stripos($request->attributes->get('_route'), $subMenuItem->getRoute()) === 0) {
                $subMenuItem->setActive(true);
                $menuItem->setActive(true);
            }


            $children[] = $menuItem;
            $subChildren = $menuItem->getChildren();
            $subChildren[] = $subMenuItem;
            $menuItem->setChildren($subChildren);
        }
    }

}
