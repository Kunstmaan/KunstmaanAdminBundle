<?php

namespace Kunstmaan\AdminBundle\Event;

/**
 * Events
 */
class Events
{

   /**
    * This event will be triggered when creating the menu.
    * It is possible to change this menu by listening to this event.
    *
    * @var string
    */
    const CONFIGURE_MENU = 'kunstmaan_admin.configureMenu';

    /**
     * This event will be triggered when the children are retrieved from a MenuItem
     * The event will need to check the children array of the MenuItem and fill it
     * if empty (if relevant)
     *
     * @var string
     */
    const CONFIGURE_MENU_CHILDREN = 'kunstmaan_admin.configureMenuChildren';

}
