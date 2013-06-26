<?php

namespace Kunstmaan\AdminBundle\DependencyInjection;

use InvalidArgumentException;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;


/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class KunstmaanAdminExtension extends Extension implements PrependExtensionInterface
{
    /**
     * Loads a specific configuration.
     *
     * @param array            $configs   An array of configuration values
     * @param ContainerBuilder $container A ContainerBuilder instance
     *
     * @throws InvalidArgumentException When provided tag is not defined in this extension
     */
    public function load(array $configs, ContainerBuilder $container)
    {

        $container->setParameter('security.acl.permission.map.class', 'Kunstmaan\AdminBundle\Helper\Security\Acl\Permission\PermissionMap');

        $configuration = new Configuration();
        $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

    }

    public function prepend(ContainerBuilder $container)
    {
        $knpMenuConfig['twig']              = true; // set to false to disable the Twig extension and the TwigRenderer
        $knpMenuConfig['templating']        = false; // if true, enables the helper for PHP templates
        $knpMenuConfig['default_renderer']  = 'twig'; // The renderer to use, list is also available by default
        $container->prependExtensionConfig('knp_menu', $knpMenuConfig);

        $fosUserConfig['db_driver']                     = 'orm'; // other valid values are 'mongodb', 'couchdb'
        $fosUserConfig['firewall_name']                 = 'main';
        $fosUserConfig['user_class']                    = 'Kunstmaan\AdminBundle\Entity\User';
        $fosUserConfig['group']['group_class']          = 'Kunstmaan\AdminBundle\Entity\Group';
        $fosUserConfig['resetting']['token_ttl']        = 86400;
        // Use this node only if you don't want the global email address for the resetting email
        $fosUserConfig['resetting']['email']['from_email']['address']        = 'admin@kunstmaan.be';
        $fosUserConfig['resetting']['email']['from_email']['sender_name']    = 'admin';
        $fosUserConfig['resetting']['email']['template']    = 'FOSUserBundle:Resetting:email.txt.twig';
        $fosUserConfig['resetting']['form']['type']                 = 'fos_user_resetting';
        $fosUserConfig['resetting']['form']['name']                 = 'fos_user_resetting_form';
        $fosUserConfig['resetting']['form']['validation_groups']    = array('ResetPassword');
        $container->prependExtensionConfig('fos_user', $fosUserConfig);

        $monologConfig['handlers']['main']['type']  = 'rotating_file';
        $monologConfig['handlers']['main']['path']  = sprintf('%s/%s', $container->getParameter('kernel.logs_dir'), $container->getParameter('kernel.environment'));
        $monologConfig['handlers']['main']['level'] = 'debug';
        $container->prependExtensionConfig('monolog', $monologConfig);

        $configs = $container->getExtensionConfig($this->getAlias());
        $config = $this->processConfiguration(new Configuration(), $configs);
    }
}
