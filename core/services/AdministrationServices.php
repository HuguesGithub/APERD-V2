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
 * @version 2.22.12.05
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
    {
        parent::__construct();
        $this->objDao = new AdministrationDaoImpl();
    }

    //////////////////////////////////////////////////
    // METHODS
    //////////////////////////////////////////////////
    
    /**
     * @param array $arrFilters
     * @return array
     * @since 2.22.12.05
     * @version 2.22.12.05
     */
    private function buildFilters($arrFilters)
    {
        $arrParams = array();
        array_push($arrParams, $this->getValueToSearch($arrFilters, self::FIELD_NOMTITULAIRE));
        array_push($arrParams, $this->getValueToSearch($arrFilters, self::FIELD_LABELPOSTE));
        return $arrParams;
    }
    
    /**
     * @param array $arrFilters
     * @param string $orderby
     * @param string $order
     * @return array
     * @since 2.22.12.05
     * @version 2.22.12.05
     */
    public function getAdministrationsWithFilters($arrFilters=array(), $orderBy='', $order='')
    {
        if ($orderBy=='') {
            $orderBy = self::FIELD_LABELPOSTE;
        }
        if ($order=='' && $orderBy!=self::SQL_ORDER_RAND) {
            $order = self::SQL_ORDER_ASC;
        }
        $arrParams = array(
            self::SQL_ORDERBY => $orderBy,
            self::SQL_ORDER => $order,
            self::SQL_LIMIT => -1,
            self::SQL_WHERE => $this->buildFilters($arrFilters),
        );
        return $this->objDao->getAdministrationsWithFilters($arrParams);
    }
    
    /**
     * @param integer $adminId
     * @return AdministrationClass
     * @since 2.22.12.05
     * @version 2.22.12.05
     */
    public function getAdministrationById($adminId)
    { return $this->objDao->getAdministrationById($adminId); }

}
