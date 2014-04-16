<?php

namespace Kunstmaan\AdminBundle\Controller;

use Doctrine\ORM\EntityManager;

use FOS\UserBundle\Controller\SecurityController as BaseController;

use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session;

/**
 * The security controller will handle the login procedure
 */
class SecurityController extends BaseController
{

    /**
     * Handle login action
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return string
     */
    public function loginAction(Request $request)
    {
        /* @var EntityManager $em */
        $em = $this->container->get('doctrine')->getManager();
        $session = $request->getSession();

        // get the error if any (works with forward and redirect -- see below)
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        } elseif (null !== $session && $session->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        } else {
            $error = '';
        }

        if ($error) {
            // @todo log $session->get(SecurityContext::LAST_USERNAME) . " tried to login to the cms but got error: " . $error
        }

        // last username entered by the user
        $lastUsername = (null === $session) ? '' : $session->get(SecurityContext::LAST_USERNAME);

        $csrfToken = $this->container->get('form.csrf_provider')->generateCsrfToken('authenticate');

        return $this->container->get('templating')->renderResponse(
            'FOSUserBundle:Security:login.html.' . $this->container->getParameter('fos_user.template.engine'),
            array(
                'last_username' => $lastUsername,
                'error'         => $error,
                'csrf_token'    => $csrfToken,
            )
        );
    }
}
