<?php

namespace Kunstmaan\AdminBundle;

use Kunstmaan\AdminBundle\DependencyInjection\Compiler\AddLogProcessorsCompilerPass;
use Kunstmaan\AdminBundle\DependencyInjection\Security\Factory\GuestUserFactory;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Bundle\SecurityBundle\DependencyInjection\SecurityExtension;

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

        $container->addCompilerPass(new AddLogProcessorsCompilerPass());

        /* @var SecurityExtension $extension */
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