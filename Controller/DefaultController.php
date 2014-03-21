<?php

namespace Kunstmaan\AdminBundle\Controller;


use Kunstmaan\AdminBundle\Form\DashboardConfigurationType;
use Kunstmaan\AdminBundle\Entity\DashboardConfiguration;
use Kunstmaan\AdminBundle\Entity\GoogleClientHelper;
use Kunstmaan\AdminBundle\Entity\GoogleAnalyticsHelper;



use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;

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
    public function indexAction()
    {
        /* @var DashboardConfiguration $dashboardConfiguration */
        $dashboardConfiguration = $this->getDoctrine()->getManager()->getRepository('KunstmaanAdminBundle:DashboardConfiguration')->findOneBy(array());
        $params         = array('dashboardConfiguration' => $dashboardConfiguration);

        $clientId       = $this->container->getParameter('google.api.client_id');
        $clientSecret   = $this->container->getParameter('google.api.client_secret');
        $redirectUri    = $this->container->getParameter('google.api.redirect_uri');
        $devKey         = $this->container->getParameter('google.api.dev_key');

        $googleClientHelper = new GoogleClientHelper($clientId, $clientSecret, $redirectUri, $devKey, $this->getDoctrine()->getManager());
        $googleClient = $googleClientHelper->getClient();

        if ($googleClientHelper->tokenIsSet()) {
            $em = $this->getDoctrine()->getManager();
            $overviews = $em->getRepository('KunstmaanAdminBundle:AnalyticsOverview')->getAll();

            $params['token'] = true;
            $params['overviews'] = $overviews;
            $params['overview'] = $overviews[2];
        } else {
            $params['token'] = false;
            $params['authUrl'] = $googleClient->createAuthUrl();
        }

        return $params;
    }

    /**
     * Return an ajax response
     *
     * @Route("/get", name="KunstmaanAdminBundle_analytics_overview_ajax")
     *
     */
    public function getOverviewAction(){

        $request = $this->get('request');
        $id = $request->request->get('overviewId');

        if($id) {
            $em = $this->getDoctrine()->getManager();
            $overview = $em->getRepository('KunstmaanAdminBundle:AnalyticsOverview')->getOverview($id);

            $serializer = new Serializer(array(new GetSetMethodNormalizer()), array('json' => new JsonEncoder()));
            $json = $serializer->serialize($overview, 'json');
            $json = json_decode($json);
            $extra['trafficDirectPercentage'] = $overview->getTrafficDirectPercentage();
            $extra['trafficReferralPercentage'] = $overview->getTrafficReferralPercentage();
            $extra['trafficSearchEnginePercentage'] = $overview->getTrafficSearchEnginePercentage();

            $return = [
                        "responseCode" => 200,
                        "overview" => $json,
                        "extra" => $extra
                        ];
       } else {
            $return = [
                        "responseCode" => 400
                        ];
       }

       $return = json_encode($return);
       return new Response($return, 200, ['Content-Type' => 'application/json']);
    }

    /**
     * The update overview action will update the data of the available overviews
     *
     * @Route("/updateOverview", name="KunstmaanAdminBundle_homepage_overview")
     * @Template()
     *
     * @return array
     */
    public function updateOverviewAction() {

        $em = $this->getDoctrine()->getManager();
        $weekOverview = $em->getRepository('KunstmaanAdminBundle:AnalyticsWeek')->getWeekOverview();
        var_dump($weekOverview);

        $clientId       = $this->container->getParameter('google.api.client_id');
        $clientSecret   = $this->container->getParameter('google.api.client_secret');
        $redirectUri    = $this->container->getParameter('google.api.redirect_uri');
        $devKey         = $this->container->getParameter('google.api.dev_key');

        $googleClientHelper = new GoogleClientHelper($clientId, $clientSecret, $redirectUri, $devKey, $this->getDoctrine()->getManager());

        $params = array();
        $googleClient = $googleClientHelper->getClient();
        if ($googleClientHelper->tokenIsSet()) {
            $params['token'] = true;
            $analyticsHelper = new GoogleAnalyticsHelper($googleClient);


            $results = $analyticsHelper->getResults(3, 2, 'ga:visits');

            $rows = $results->getRows();

            echo "<pre>";
            print_r($rows);
            echo "</pre>";

        } else {
            $params['token'] = false;
            $params['authUrl'] = $googleClient->createAuthUrl();
        }

        return [];
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
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();

        /* @var DashboardConfiguration $dashboardConfiguration */
        $dashboardConfiguration = $this->getDoctrine()->getManager()->getRepository('KunstmaanAdminBundle:DashboardConfiguration')->findOneBy(array());
        if (is_null($dashboardConfiguration)) {
            $dashboardConfiguration = new DashboardConfiguration();
        }

        $form = $this->createForm(new DashboardConfigurationType(), $dashboardConfiguration);

        if ('POST' == $request->getMethod()) {
            $form->bind($request);
            if ($form->isValid()) {
                $em->persist($dashboardConfiguration);
                $em->flush();
                $this->get('session')->getFlashBag()->add('success', 'The welcome page has been edited!');

                return new RedirectResponse($this->generateUrl('KunstmaanAdminBundle_homepage'));
            }
        }

        return array(
                'form' => $form->createView(),
                'dashboardConfiguration' => $dashboardConfiguration
                );
    }
}
