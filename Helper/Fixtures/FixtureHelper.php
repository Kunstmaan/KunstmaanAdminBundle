<?php
namespace Kunstmaan\AdminBundle\Helper\Fixtures;

use Doctrine\ORM\EntityManager;
use Kunstmaan\AdminBundle\Modules\ClassLookup;
use Kunstmaan\AdminBundle\Entity\Permission;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Kunstmaan\AdminNodeBundle\Entity\Node;
use Kunstmaan\AdminNodeBundle\Entity\HasNodeInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;


class FixtureHelper
{
    /**
     * @param \Doctrine\ORM\EntityManager $manager
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
     * @param array $titles
     * @param string $class
     * @param \Kunstmaan\AdminNodeBundle\Entity\HasNodeInterface $parent
     * @param string $internalName
     * @return \Kunstmaan\AdminNodeBundle\Entity\AbstractPage
     */
    public function createContentPage(EntityManager $manager, ContainerInterface $container, $titles, $class, HasNodeInterface $parent = null, $internalName = null)
    {
        $defaultLocale = $container->getParameter("defaultlocale");
        $adminUser = $manager->getRepository('KunstmaanAdminBundle:User')->findOneBy(array('username' => 'Admin'));
        $defaultTitle = $titles[$defaultLocale];

        $contentPage = new $class();
        $contentPage->setTitle($defaultTitle);
        if ($parent){
            $contentPage->setParent($parent);
        }
        $manager->persist($contentPage);
        $manager->flush();

        $node = $manager->getRepository('KunstmaanAdminNodeBundle:Node')->createNodeFor($contentPage, $defaultLocale, $adminUser, $internalName);
        $this->initPermissions($manager, $node);

        $requiredLocales = $container->getParameter("requiredlocales");
        $allLocales = explode("|", $requiredLocales);
        foreach ($allLocales as $locale) {
            if ($locale != $defaultLocale) {

                if (isset($titles[$locale])) {
                    $title = $titles[$locale];
                } else {
                    $title = $defaultTitle;
                }

                $translatedContentPage = new $class();
                $translatedContentPage->setTitle($title);
                $manager->persist($translatedContentPage);
                $manager->flush();

                $manager->getRepository('KunstmaanAdminNodeBundle:NodeTranslation')->createNodeTranslationFor($translatedContentPage, $locale, $node, $adminUser);
            }
        }

        return $contentPage;
    }

    /**
     * @param \Doctrine\Common\DataFixtures\AbstractFixture $fixture
     * @param \Doctrine\ORM\EntityManager $manager
     * @param \Kunstmaan\AdminNodeBundle\Entity\Node $node
     */
    protected function initPermissions(EntityManager $manager, Node $node)
    {
        $adminGroup         = $manager->getRepository('KunstmaanAdminBundle:Group')->findOneBy(array('name' => 'Administrators'));
        $guestGroup         = $manager->getRepository('KunstmaanAdminBundle:Group')->findOneBy(array('name' => 'Guests'));

        //Page 1
        //----------------------------------

        $page1Permission2 = new Permission();
        $page1Permission2->setRefId($node->getId());
        $page1Permission2->setRefEntityname(ClassLookup::getClass($node));
        $page1Permission2->setRefGroup($adminGroup);
        $page1Permission2->setPermissions('|read:1|write:1|delete:1|');
        $manager->persist($page1Permission2);
        $manager->flush();

        $page1Permission3 = new Permission();
        $page1Permission3->setRefId($node->getId());
        $page1Permission3->setRefEntityname(ClassLookup::getClass($node));
        $page1Permission3->setRefGroup($guestGroup);
        $page1Permission3->setPermissions('|read:1|write:0|delete:0|');
        $manager->persist($page1Permission3);
        $manager->flush();
    }


}
