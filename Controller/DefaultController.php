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
        $dashboardConfiguration = $this->getDoctrine()->getManager()->getRepository('KunstmaanAdminBundle:DashboardConfiguration')->findOneBy(array());
        $params         = array('dashboardConfiguration' => $dashboardConfiguration);

        // get API client
        $googleClientHelper = $this->container->get('kunstmaan_admin.googleclienthelper');

        if ($googleClientHelper->tokenIsSet() && $googleClientHelper->propertyIsSet()) {
            $em = $this->getDoctrine()->getManager();
            $overviews = $em->getRepository('KunstmaanAdminBundle:AnalyticsOverview')->getAll();

            $params['token'] = true;
            $params['overviews'] = array();
            if ($overviews) { // if this is an array with overviews
                // set the overviews param
                $params['overviews'] = $overviews;
                // set the default overview
                $params['overview'] = $overviews[0];
                if (sizeof($overviews == 5)) { // if all overviews are present
                    // set the default overview to the middle one
                    $params['overview'] = $overviews[2];
                }
                $params['referrals'] = $params['overview']->getReferrals()->toArray();
                $params['searches'] = $params['overview']->getSearches()->toArray();
            } else {
                // if no overviews are yet configured
                return $this->render(
                    'KunstmaanAdminBundle:Analytics:errorOverviews.html.twig',
                    array()
                );
            }
        } else if ($googleClientHelper->tokenIsSet()) {
            return $this->redirect($this->generateUrl('KunstmaanAdminBundle_PropertySelection'));
        } else {
            $params['token'] = false;
            $currentRoute = $request->attributes->get('_route');
            $currentUrl = $this->get('router')->generate($currentRoute, array(), true);
            $params['url'] = $currentUrl . 'setToken/';
            $googleClient = $googleClientHelper->getClient();
            $params['authUrl'] = $googleClient->createAuthUrl();
        }

        return $params;
    }

    /**
     *
     *
     * @Route("/setToken/", name="KunstmaanAdminBundle_setToken")
     *
     * @param Request $request
     *
     * @return array
     */
    public function setTokenAction(Request $request)
    {
        $code = $request->query->get('code');

        if (isset($code)) {
            // get API client
            $googleClientHelper = $this->container->get('kunstmaan_admin.googleclienthelper');


            $googleClientHelper->getClient()->authenticate();
            $googleClientHelper->saveToken($googleClientHelper->getClient()->getAccessToken());
            return $this->redirect($this->generateUrl('KunstmaanAdminBundle_PropertySelection'));
        }
        return $this->redirect($this->generateUrl('KunstmaanAdminBundle_homepage'));
    }

    /**
     *
     *
     * @Route("/selectWebsite", name="KunstmaanAdminBundle_PropertySelection")
     * @Template()
     *
     * @param Request $request
     *
     * @return array
     */
    public function propertySelectionAction(Request $request)
    {
        if (null !== $request->request->get('properties')) {
            $em = $this->getDoctrine()->getManager();
            $property = $em->getRepository('KunstmaanAdminBundle:AnalyticsProperty')->getProperty();

            $parts = explode("::", $request->request->get('properties'));
            $property->setPropertyId($parts[0]);
            $property->setAccountId($parts[1]);

            $em->persist($property);
            $em->flush();
            return $this->redirect($this->generateUrl('KunstmaanAdminBundle_homepage'));
        }

        // get API client
        $googleClientHelper = $this->container->get('kunstmaan_admin.googleclienthelper');

        // get Helper
        $googleClient = $googleClientHelper->getClient();
        $analyticsHelper = $this->container->get('kunstmaan_admin.googleanalyticshelper');
        $analyticsHelper->init($googleClientHelper);
        $properties = $analyticsHelper->getProperties();

        return array('properties' => $properties);
    }

    /**
     * Return an ajax response
     *
     * @Route("/getOverview/{id}", requirements={"id" = "\d+"}, name="KunstmaanAdminBundle_analytics_overview_ajax")
     *
     */
    public function getOverviewAction($id){
        $request = $this->get('request');

        if($id) {
            $em = $this->getDoctrine()->getManager();
            $overview = $em->getRepository('KunstmaanAdminBundle:AnalyticsOverview')->getOverview($id);

            $extra['trafficDirectPercentage'] = $overview->getTrafficDirectPercentage();
            $extra['trafficReferralPercentage'] = $overview->getTrafficReferralPercentage();
            $extra['trafficSearchEnginePercentage'] = $overview->getTrafficSearchEnginePercentage();
            $extra['dayData'] = json_decode($overview->getDayData());

            $extra['referrals'] = array();
            foreach ($overview->getReferrals()->toArray() as $key=>$referral) {
                $extra['referrals'][$key]['visits'] = $referral->getVisits();
                $extra['referrals'][$key]['name'] = $referral->getName();
            }
            $extra['searches'] = array();
            foreach ($overview->getSearches()->toArray() as $key=>$search) {
                $extra['searches'][$key]['visits'] = $search->getVisits();
                $extra['searches'][$key]['name'] = $search->getName();
            }

            $overviewData = array(
                'dayData' => $overview->getDayData(),
                'useDayData' => $overview->getUseDayData(),
                'title' => $overview->getTitle(),
                'timespan' => $overview->getTimespan(),
                'startOffset' => $overview->getStartOffset(),
                'visits' => $overview->getVisits(),
                'returningVisits' => $overview->getReturningVisits(),
                'newVisits' => $overview->getNewVisits(),
                'pageViews' => $overview->getPageViews(),
                'trafficDirect' => $overview->getTrafficDirect(),
                'trafficReferral' => $overview->getTrafficReferral(),
                'trafficSearchEngine' => $overview->getTrafficSearchEngine(),
                );

            $return = array(
                        "responseCode" => 200,
                        "overview" => $overviewData,
                        "extra" => $extra
                        );
       } else {
            $return = array(
                        "responseCode" => 400
                        );
       }

       $return = json_encode($return);
       return new Response($return, 200, array('Content-Type' => 'application/json'));
    }

    /**
     * Return an ajax response
     *
     * @Route("/getDailyOverview", name="KunstmaanAdminBundle_analytics_dailyoverview_ajax")
     *
     */
    public function getDailyOverviewAction(){
        $request = $this->get('request');
        $em = $this->getDoctrine()->getManager();
        $dailyOverview = $em->getRepository('KunstmaanAdminBundle:AnalyticsDailyOverview')->getOverview();

        $return = array(
                    "responseCode" => 200,
                    "dailyOverview" => json_decode($dailyOverview->getData())
                    );

       $return = json_encode($return);
       return new Response($return, 200, array('Content-Type' => 'application/json'));
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
