<?php

namespace Kunstmaan\AdminBundle\Tests\Helper\Security\OAuth;

use DateTime;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Kunstmaan\AdminBundle\Entity\Group;
use Kunstmaan\AdminBundle\Entity\User;
use Kunstmaan\AdminBundle\Helper\Security\OAuth\OAuthUserCreator;
use Kunstmaan\AdminBundle\Helper\Security\OAuth\OAuthUserFinderInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

class OAuthUserCreatorTest extends TestCase
{
    /**
     * @var OAuthUserCreator
     */
    private $object;

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var OAuthUserFinderInterface
     */
    private $finder;

    private function getEm(): MockObject
    {
        if (!isset($this->em)) {
            $this->em = $this->createMock(EntityManager::class);
        }

        return $this->em;
    }

    private function getFinder(): MockObject
    {
        if (!isset($this->finder)) {
            $this->finder = $this->createMock(OAuthUserFinderInterface::class);
        }

        return $this->finder;
    }

    public function setUp(): void
    {
        $em = $this->getEm();
        $finder = $this->getFinder();
        $object = new OAuthUserCreator($em, [[
            'domain_name' => 'gmail.com',
            'access_levels' => ['ROLE_ADMIN'],
        ]], User::class, $finder);
        $this->object = $object;
    }

    public function testGetOrCreateUserReturnsNull()
    {
        $object = $this->object;
        $user = $object->getOrCreateUser('madman@work.com', 12345679);
        $this->assertNull($user);
    }

    public function testGetOrCreateUserReturnsUser()
    {
        $object = $this->object;
        $this->getFinder()->expects($this->once())->method('findUserByGoogleSignInData')->willReturn(new User());
        $mockGroup = $this->createMock(Group::class);
        $mockRepo = $this->createMock(EntityRepository::class);
        $mockRepo->expects($this->once())->method('findOneBy')->willReturn($mockGroup);
        $this->getEm()->expects($this->once())->method('getRepository')->willReturn($mockRepo);
        $this->getEm()->expects($this->once())->method('persist')->willReturn(true);
        $this->getEm()->expects($this->once())->method('flush')->willReturn(true);
        $user = $object->getOrCreateUser('madman@gmail.com', 12345679);
        $this->assertInstanceOf(User::class, $user);
    }

    public function testGetOrCreateUserCreatesCorrectUserClass()
    {
        $object = $this->object;
        $this->getFinder()->expects($this->once())->method('findUserByGoogleSignInData')->willReturn(new DateTime());
        $mockGroup = $this->createMock(Group::class);
        $mockRepo = $this->createMock(EntityRepository::class);
        $mockRepo->expects($this->once())->method('findOneBy')->willReturn($mockGroup);
        $this->getEm()->expects($this->once())->method('getRepository')->willReturn($mockRepo);
        $this->getEm()->expects($this->once())->method('persist')->willReturn(true);
        $this->getEm()->expects($this->once())->method('flush')->willReturn(true);
        $user = $object->getOrCreateUser('madman@gmail.com', 12345679);
        $this->assertInstanceOf(User::class, $user);
    }

    /**
     * @throws \ReflectionException
     */
    public function testAccessLevelsReturnsNull()
    {
        $em = $this->getEm();
        $finder = $this->getFinder();
        $object = new OAuthUserCreator($em, [[
            'domain_name' => 'gmail.com',
            'access_levels' => ['ROLE_ADMIN'],
        ]], User::class, $finder);

        $mirror = new ReflectionClass(OAuthUserCreator::class);
        $method = $mirror->getMethod('getAccessLevels');
        $method->setAccessible(true);
        $levels = $method->invoke($object, 'fake@mail.com');
        $this->assertNull($levels);
    }
}
