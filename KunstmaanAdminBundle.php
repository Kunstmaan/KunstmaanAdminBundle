<?php

namespace Kunstmaan\AdminBundle;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Kunstmaan\AdminBundle\DependencyInjection\Security\Factory\GuestUserFactory;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * KunstmaanAdminBundle
 */
class KunstmaanAdminBundle extends Bundle
{

    /**
     * @param ContainerBuilder $container A ContainerBuilder instance
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $extension = $container->getExtension('security');
        $extension->addSecurityListenerFactory(new GuestUserFactory());
    }

    /**
     * @return string The Bundle parent name it overrides or null if no parent
     */
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
