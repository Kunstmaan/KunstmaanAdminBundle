<?php

namespace Kunstmaan\AdminBundle\EventListener;

use Kunstmaan\AdminBundle\Event\ConfigureMenuChildrenEvent;

class ConfigureMenuChildrenListener
{

    public function __construct()
    {

    }

    public function onMenuChildrenConfigure(ConfigureMenuChildrenEvent $event)
    {
        $menu = $event->getMenu();
        $factory = $event->getFactory();

        if ('Settings' == $menu->getName()) {
            $usersMenu = $menu->addChild($factory->createItem('Users', array('route' => 'KunstmaanAdminBundle_settings_users')));
            $usersMenu->setAttribute('rel', 'Users');
            $groupsMenu = $menu->addChild($factory->createItem('Groups', array('route' => 'KunstmaanAdminBundle_settings_groups')));
            $groupsMenu->setAttribute('rel', 'Groups');
            $rolesMenu = $menu->addChild($factory->createItem('Roles', array('route' => 'KunstmaanAdminBundle_settings_roles')));
            $rolesMenu->setAttribute('rel', 'Roles');
            $logsMenu = $menu->addChild($factory->createItem('Logs', array('route' => 'KunstmaanAdminBundle_settings_logs')));
            $logsMenu->setAttribute('rel', 'Logs');
        } else if ('Modules' == $menu->getName()) {
        }
    }
}