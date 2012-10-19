<?php

namespace Kunstmaan\AdminBundle\Helper\Menu;

/**
 * A MenuItem is part of the menu in the admin interface, this will be build by the {@link MenuBuilder}
 */
class OldMenuItem
{
    /**
     * @var OldMenuBuilder
     */
    private $menu;

    /**
     * @var string
     */
    private $internalName;

    /**
     * @var string
     */
    private $role;

    /**
     * @var OldMenuItem
     */
    private $parent;

    /**
     * @var string
     */
    private $route;

    /**
     * @var array
     */
    private $routeParams = array();

    /**
     * @var boolean
     */
    private $active = false;

    /**
     * @var OldMenuItem[]
     */
    private $children = null;

    /**
     * @var array
     */
    private $attributes = array();

    /**
     * @var boolean
     */
    private $appearInNavigation = true;

    /**
     * @var int
     */
    private $weight = -50;

    /**
     * Construct the MenuItem
     *
     * @param OldMenuBuilder $menu
     */
    public function __construct(OldMenuBuilder $menu)
    {
        $this->menu = $menu;
    }

    /**
     * Get menu builder
     *
     * @return OldMenuBuilder
     */
    public function getMenu()
    {
        return $this->menu;
    }

    /**
     * Get internal name
     *
     * @return string
     */
    public function getInternalName()
    {
        return $this->internalName;
    }

    /**
     * Set internal name
     *
     * @param string $internalName
     *
     * @return OldMenuItem
     */
    public function setInternalName($internalName)
    {
        $this->internalName = $internalName;

        return $this;
    }

    /**
     * Get role
     *
     * @return string
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Set role
     *
     * @param string $role
     *
     * @return OldMenuItem
     */
    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Get parent menu item
     *
     * @return OldMenuItem|null
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set parent menu item
     *
     * @param OldMenuItem|null  $parent
     *
     * @return OldMenuItem
     */
    public function setParent(OldMenuItem $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get route for menu item
     *
     * @return string
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * Set route and parameters for menu item
     *
     * @param string $route  The route
     * @param array  $params The route parameters
     *
     * @return OldMenuItem
     */
    public function setRoute($route, array $params = array())
    {
        $this->route       = $route;
        $this->routeParams = $params;

        return $this;
    }

    /**
     * Get route parameters for menu item
     *
     * @return array
     */
    public function getRouteParams()
    {
        return $this->routeParams;
    }

    /**
     * Set route parameters
     *
     * @param array $routeParams
     *
     * @return OldMenuItem
     */
    public function setRouteParams(array $routeParams = array())
    {
        $this->routeParams = $routeParams;

        return $this;
    }

    /**
     * Get children of current menu item
     *
     * @return OldMenuItem[]
     */
    public function getChildren()
    {
        if (is_null($this->children)) {
            $this->children = $this->menu->getChildren($this);
        }

        return $this->children;
    }

    /**
     * Get children of current menu item that have the appearInNavigation flag set
     *
     * @return OldMenuItem[]
     */
    public function getNavigationChildren()
    {
        $result   = array();
        $children = $this->getChildren();
        foreach ($children as $child) {
            if ($child->getAppearInNavigation()) {
                $result[] = $child;
            }
        }

        return $result;
    }

    /**
     * Return top children of current menu item
     *
     * @return TopMenuItem[]
     */
    public function getTopChildren()
    {
        $result   = array();
        $children = $this->getChildren();
        foreach ($children as $child) {
            if ($child instanceof TopMenuItem) {
                $result[] = $child;
            }
        }

        return $result;
    }

    /**
     * Add attributes
     *
     * @param array $attributes
     *
     * @return OldMenuItem
     */
    public function addAttributes($attributes)
    {
        $this->attributes = array_merge($this->attributes, $attributes);

        return $this;
    }

    /**
     * Get attributes
     *
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * Get menu item active state
     *
     * @return bool
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Set menu item active state
     *
     * @param bool $active
     *
     * @return OldMenuItem
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get appearInNavigation flag
     *
     * @return bool
     */
    public function getAppearInNavigation()
    {
        return $this->appearInNavigation;
    }

    /**
     * Set appearInNavigation flag
     *
     * @param bool $appearInNavigation
     *
     * @return OldMenuItem
     */
    public function setAppearInNavigation($appearInNavigation)
    {
        $this->appearInNavigation = $appearInNavigation;

        return $this;
    }

    /**
     * Get weight
     *
     * @return int
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * Set weight
     *
     * @param int $weight
     *
     * @return OldMenuItem
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;

        return $this;
    }

}
