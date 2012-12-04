<?php

namespace Kunstmaan\AdminBundle\Event;

/**
 * AdminBundle events
 */
class Events
{

    /**
     * The onDeepClone event occurs for a given entity while it's being deep cloned. here it's possible to set
     * certain fields of the cloned entity before it's being saved
     *
     * @var string
     */
    const DEEP_CLONE_AND_SAVE  = 'kunstmaan_admin.onDeepCloneAndSave';

    /**
     * The postDeepClone event occurs for a given entity after it has been deep cloned.
     *
     * @var string
     */
    const POST_DEEP_CLONE_AND_SAVE = 'kunstmaan_admin.postDeepCloneAndSave';
    
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
