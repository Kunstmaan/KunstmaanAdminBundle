<?php

namespace Kunstmaan\AdminBundle\Controller;

use Kunstmaan\AdminBundle\Form\DashboardConfigurationType;
use Kunstmaan\AdminBundle\Entity\DashboardConfiguration;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

/**
 * The default controller is used to render the main screen the users see when they log in to the admin
 */
class DefaultController extends Controller
{

    /**
     * The index action will render the main screen the users see when they log in in to the admin
     *
     * @Route("/", name="KunstmaanAdminBundle_homepage")
     * @Template()
     *
     * @return array
     */
    public function indexAction(Request $request)
    {
        /* @var DashboardConfiguration $dashboardConfiguration */
        $dashboardConfiguration = $this->getDoctrine()->getManager()->getRepository(
          'KunstmaanAdminBundle:DashboardConfiguration'
        )->findOneBy(array());
        $params                 = array('dashboardConfiguration' => $dashboardConfiguration);

        // get API client
        try {
            $googleClientHelper = $this->container->get('kunstmaan_admin.googleclienthelper');
        } catch (\Exception $e) {
            // catch exception thrown by the googleClientHelper if one or more parameters in parameters.yml is not set
            $currentRoute  = $request->attributes->get('_route');
            $currentUrl    = $this->get('router')->generate($currentRoute, array(), true);
            $params['url'] = $currentUrl . 'setToken/';

            return $this->render('KunstmaanAdminBundle:Analytics:connect.html.twig', $params);
        }

        // if token not set
        if (!$googleClientHelper->tokenIsSet()) {
            $currentRoute  = $request->attributes->get('_route');
            $currentUrl    = $this->get('router')->generate($currentRoute, array(), true);
            $params['url'] = $currentUrl . 'setToken/';

            $googleClient      = $googleClientHelper->getClient();
            $params['authUrl'] = $googleClient->createAuthUrl();
            $params['redirect_uri'] = $this->get('router')->generate('KunstmaanAdminBundle_setToken', array(), true);

            return $this->render('KunstmaanAdminBundle:Analytics:connect.html.twig', $params);
        }

        // if propertyId not set
        if (!$googleClientHelper->propertyIsSet()) {
            return $this->redirect($this->generateUrl('KunstmaanAdminBundle_PropertySelection'));
        }

        // if profileId not set
        if (!$googleClientHelper->profileIsSet()) {
            return $this->redirect($this->generateUrl('KunstmaanAdminBundle_ProfileSelection'));
        }

        // if setup is complete
        $em        = $this->getDoctrine()->getManager();
        $overviews = $em->getRepository('KunstmaanAdminBundle:AnalyticsOverview')->getAll();

        $params['token']     = true;
        $params['overviews'] = array();

        // if no overviews are yet configured
        if (!$overviews) {
            return $this->render(
                'KunstmaanAdminBundle:Analytics:errorOverviews.html.twig',
                array()
            );
        }

        // set the overviews param
        $params['overviews'] = $overviews;
        // set the default overview
        $params['overview'] = $overviews[0];
        if (sizeof($overviews) == 5) { // if all overviews are present
            // set the default overview to the middle one
            $params['overview'] = $overviews[2];
        }
        $params['referrals'] = $params['overview']->getReferrals()->toArray();
        $params['searches']  = $params['overview']->getSearches()->toArray();


        return $params;
    }

    /**
     * The admin of the index page
     *
     * @Route("/adminindex", name="KunstmaanAdminBundle_homepage_admin")
     * @Template()
     *
     * @return array
     */
    public function editIndexAction()
    {
        /* @var $em EntityManager */
        $em      = $this->getDoctrine()->getManager();
        $request = $this->getRequest();

        /* @var DashboardConfiguration $dashboardConfiguration */
        $dashboardConfiguration = $this->getDoctrine()->getManager()->getRepository(
          'KunstmaanAdminBundle:DashboardConfiguration'
        )->findOneBy(array());
        if (is_null($dashboardConfiguration)) {
            $dashboardConfiguration = new DashboardConfiguration();
        }

        $form = $this->createForm(new DashboardConfigurationType(), $dashboardConfiguration);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em->persist($dashboardConfiguration);
                $em->flush();
                $this->get('session')->getFlashBag()->add('success', 'The welcome page has been edited!');

                return new RedirectResponse($this->generateUrl('KunstmaanAdminBundle_homepage'));
            }
        }

        return array(
          'form'                   => $form->createView(),
          'dashboardConfiguration' => $dashboardConfiguration
        );
    }
}
