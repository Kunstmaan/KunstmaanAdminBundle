<?php
/**
 * Created by JetBrains PhpStorm.
 * User: kris
 * Date: 15/11/11
 * Time: 22:23
 * To change this template use File | Settings | File Templates.
 */

namespace Kunstmaan\AdminBundle\AdminList;

use Kunstmaan\AdminListBundle\AdminList\AdminListFilter;
use Kunstmaan\AdminListBundle\AdminList\FilterDefinitions\DateFilterType;
use Kunstmaan\AdminListBundle\AdminList\FilterDefinitions\StringFilterType;
use Kunstmaan\AdminListBundle\AdminList\AbstractAdminListConfigurator;

class RoleAdminListConfigurator extends AbstractAdminListConfigurator{

    public function buildFilters(AdminListFilter $builder) {
        $builder->add('role', new StringFilterType("role"), "Role");
    }
    
    public function buildFields() {
    	$this->addField("role", "Role", true);
    }

    public function canAdd() {
        return true;
    }

    public function getAddUrlFor($params=array()) {
    	return array(
            'role' => array('path' => 'KunstmaanAdminBundle_settings_roles_add', 'params' => $params)
    	);
    }

    public function canEdit() {
    	return true;
    }
    
    public function getEditUrlFor($item) {
    	return array('path' => 'KunstmaanAdminBundle_settings_roles_edit', 'params' => array('role_id' => $item->getId()));
    }

    public function canDelete($item) {
        return true;
    }

    public function getAdminType($item) {
        return null;
    }

    public function getRepositoryName() {
        return 'KunstmaanAdminBundle:Role';
    }

    public function adaptQueryBuilder($querybuilder, $params=array()) {
        parent::adaptQueryBuilder($querybuilder);
    }


    public function getValue($item, $columnName) {
        $result = parent::getValue($item, $columnName);

        if($result instanceof \Doctrine\ORM\PersistentCollection) {
            $results = "";
            foreach($result as $entry) {
                $results[] = $entry->getRole();
            }

            return implode(', ', $results);
        }

        if(is_array($result)) {
            return implode(', ', $result);
        }

        return $result;
    }
}
