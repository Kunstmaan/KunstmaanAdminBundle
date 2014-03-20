<?php

namespace Kunstmaan\AdminBundle\AdminList;

use Doctrine\ORM\EntityManager;

use Kunstmaan\AdminBundle\Form\AnalyticsOverviewAdminType;
use Kunstmaan\AdminListBundle\AdminList\FilterType\ORM;
use Kunstmaan\AdminListBundle\AdminList\Configurator\AbstractDoctrineORMAdminListConfigurator;
use Kunstmaan\AdminBundle\Helper\Security\Acl\AclHelper;

/**
 * The admin list configurator for AnalyticsOverview
 */
class AnalyticsOverviewAdminListConfigurator extends AbstractDoctrineORMAdminListConfigurator
{

    /**
     * @param EntityManager $em        The entity manager
     * @param AclHelper     $aclHelper The acl helper
     */
    public function __construct(EntityManager $em, AclHelper $aclHelper = null)
    {
        parent::__construct($em, $aclHelper);
        $this->setAdminType(new AnalyticsOverviewAdminType());
    }

    /**
     * Configure the visible columns
     */
    public function buildFields()
    {
        $this->addField('title', 'Title', true);
        $this->addField('timespan', 'Timespan (days)', true);
        $this->addField('startOffset', 'Start offset (days)', true);
        $this->addField('visits', 'Visits', true);
        $this->addField('pageviews', 'Pageviews', true);
        $this->addField('newVisits', 'New visitors', true);
        $this->addField('returningVisits', 'Returning visitors', true);
        $this->addField('trafficDirect', 'Direct visitors', true);
        $this->addField('trafficReferral', 'Visitors from referrals', true);
        $this->addField('trafficSearchEngine', 'Visitors from search engines', true);
        $this->addField('getTopReferrals', 'Top referrals', true);
        $this->addField('getTopSearches', 'Top searches', true);
    }

    /**
     * Build filters for admin list
     */
    public function buildFilters()
    {
        $this->addFilter('title', new ORM\StringFilterType('title'), 'Title');
        $this->addFilter('timespan', new ORM\NumberFilterType('timespan'), 'Timespan');
        $this->addFilter('visits', new ORM\NumberFilterType('visits'), 'Visits');
        $this->addFilter('pageviews', new ORM\NumberFilterType('Pageviews'), 'Pageviews');
        $this->addFilter('newVisits', new ORM\NumberFilterType('NewVisits'), 'NewVisits');
        $this->addFilter('returningVisits', new ORM\NumberFilterType('ReturningVisits'), 'Pageviews');
    }

    /**
     * Get bundle name
     *
     * @return string
     */
    public function getBundleName()
    {
        return 'KunstmaanAdminBundle';
    }

    /**
     * Get entity name
     *
     * @return string
     */
    public function getEntityName()
    {
        return 'AnalyticsOverview';
    }

}
