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
        if ('Settings' == $event->getMenu()->getName()) {
            $usersMenu = $event->getMenu()->addChild($event->getFactory()->createItem('Users', array('route' => 'KunstmaanAdminBundle_settings_users')));
            $usersMenu->setAttribute('rel', 'Users');
            $groupsMenu = $event->getMenu()->addChild($event->getFactory()->createItem('Groups', array('route' => 'KunstmaanAdminBundle_settings_groups')));
            $groupsMenu->setAttribute('rel', 'Groups');
            $rolesMenu = $event->getMenu()->addChild($event->getFactory()->createItem('Roles', array('route' => 'KunstmaanAdminBundle_settings_roles')));
            $rolesMenu->setAttribute('rel', 'Roles');
        }
    }
}