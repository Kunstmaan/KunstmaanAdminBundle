<?php

namespace Kunstmaan\AdminBundle\Helper\Menu;

use Kunstmaan\AdminBundle\Helper\Menu\MenuAdaptorInterface;
use Kunstmaan\AdminBundle\Helper\Menu\OldMenuBuilder;
use Kunstmaan\AdminBundle\Helper\Menu\OldMenuItem;

use Symfony\Component\HttpFoundation\Request;

/**
 * MenuAdaptor to add the Modules MenuItem to the top menu
 */
class ModulesMenuAdaptor implements MenuAdaptorInterface
{
    /**
     * In this method you can add children for a specific parent, but also remove and change the already created children
     *
     * @param OldMenuBuilder $menu      The MenuBuilder
     * @param OldMenuItem[]  &$children The current children
     * @param OldMenuItem    $parent    The parent Menu item
     * @param Request     $request   The Request
     */
    public function adaptChildren(OldMenuBuilder $menu, array &$children, OldMenuItem $parent = null, Request $request = null)
    {
        if (is_null($parent)) {
            $menuItem = new TopMenuItem($menu);
            $menuItem->setRoute('KunstmaanAdminBundle_modules')
                ->setInternalName("Modules")
                ->setParent($parent);
            if (stripos($request->attributes->get('_route'), $menuItem->getRoute()) === 0) {
                $menuItem->setActive(true);
            }
            $children[] = $menuItem;
        }
    }

}
