<?php

namespace Kunstmaan\AdminBundle\EventListener;

use Kunstmaan\AdminBundle\Event\ConfigureTopMenuChildrenEvent;

class ConfigureMenuChildrenListener
{

    public function __construct()
    {

    }

    public function onMenuChildrenConfigure(ConfigureTopMenuChildrenEvent $event)
    {
        $menu = $event->getMenu();
        $factory = $event->getFactory();

        if ('Settings' == $menu->getName()) {
            $menu->addChild($factory->createItem('Users', array('route' => 'KunstmaanAdminBundle_settings_users')));
            $menu->addChild($factory->createItem('Groups', array('route' => 'KunstmaanAdminBundle_settings_groups')));
            $menu->addChild($factory->createItem('Roles', array('route' => 'KunstmaanAdminBundle_settings_roles')));
            $menu->addChild($factory->createItem('Logs', array('route' => 'KunstmaanAdminBundle_settings_logs')));
        } else if ('Modules' == $menu->getName()) {
        }
    }
}