<?php

namespace Kunstmaan\AdminBundle\Tests\EventListener;

use Kunstmaan\AdminBundle\Entity\User;
use Kunstmaan\AdminBundle\EventListener\LoginListener;
use Kunstmaan\AdminBundle\Helper\VersionCheck\VersionChecker;
use PHPUnit\Framework\TestCase;
use Psr\Log\AbstractLogger;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

class LoginListenerTest extends TestCase
{
    public function testListener()
    {
        $logger = $this->createMock(AbstractLogger::class);
        $version = $this->createMock(VersionChecker::class);
        $token = $this->createMock(TokenInterface::class);
        $user = $this->createMock(User::class);
        $event = new InteractiveLoginEvent(new Request(), $token);

        $logger->expects($this->once())->method('info')->willReturn(true);
        $version->expects($this->once())->method('periodicallyCheck')->willReturn(true);
        $token->expects($this->once())->method('getUser')->willReturn($user);

        $listener = new LoginListener($logger, $version);
        $listener->onSecurityInteractiveLogin($event);
    }
}
