<?php
namespace core\services;

use core\daoimpl\AdministrationDaoImpl;
use core\domain\AdministrationClass;

if (!defined('ABSPATH')) {
    die('Forbidden');
}
/**
 * Classe AdministrationServices
 * @author Hugues
 * @since 2.22.12.05
 * @version 2.22.12.08
 */
class AdministrationServices extends LocalServices
{
    //////////////////////////////////////////////////
    // CONSTRUCT
    //////////////////////////////////////////////////
    /**
     * Class constructor
     * @since 2.22.12.05
     * @version 2.22.12.05
     */
    public function __construct()
    { $this->objDao = new AdministrationDaoImpl(); }

    //////////////////////////////////////////////////
    // METHODS
    //////////////////////////////////////////////////
    
    /**
     * @param array $arrFilters
     * @return array
     * @since 2.22.12.05
     * @version 2.22.12.08
     */
    public function buildFilters($arrFilters)
    {
        $arrParams = array();
        array_push($arrParams, $this->getValueToSearch($arrFilters, self::FIELD_NOMTITULAIRE));
        array_push($arrParams, $this->getValueToSearch($arrFilters, self::FIELD_LABELPOSTE));
        return $arrParams;
    }
    
    /**
     * @param AdministrationClass $obj
     * @since 2.22.12.07
     * @version 2.22.12.07
     */
    public function delete($obj)
    { $this->objDao->deleteAdministration(array($obj->getField(self::FIELD_ID))); }
    
    /**
     * @param integer $adminId
     * @return AdministrationClass
     * @since 2.22.12.05
     * @version 2.22.12.05
     */
    public function getAdministrationById($adminId)
    { return $this->objDao->getAdministrationById($adminId); }
    
    /**
     * @param array $arrFilters
     * @param string $orderby
     * @param string $order
     * @return array
     * @since 2.22.12.05
     * @version 2.22.12.08
     */
    public function getAdministrationsWithFilters(
        $arrFilters=array(),
        $orderBy=self::FIELD_NOMTITULAIRE,
        $order=self::SQL_ORDER_ASC
    ) {
        $arrParams = $this->initRequestParams($arrFilters, $orderBy, $order);
        return $this->objDao->getAdministrationsWithFilters($arrParams);
    }

    /**
     * @param AdministrationClass $obj
     * @since 2.22.12.07
     * @version 2.22.12.08
     */
    public function insert(&$obj)
    {
        $id = $this->objDao->insertAdministration($this->getParamsForInsert($obj));
        $obj->setField(self::FIELD_ID, $id);
    }
    
    /**
     * @param AdministrationClass $obj
     * @since 2.22.12.07
     * @version 2.22.12.08
     */
    public function update($obj)
    { $this->objDao->updateAdministration($this->getParamsForUpdate($obj)); }
}
