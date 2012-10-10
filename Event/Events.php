<?php

namespace Kunstmaan\AdminBundle\Event;

/**
 * Events
 */
class Events
{

   /**
    * This event will be triggered when creating the top menu.
    * It is possible to change this menu using this event.
    *
    * @var string
    */
   const CONFIGURE_TOP_MENU = 'kunstmaan_admin.configureTopMenu';

}
