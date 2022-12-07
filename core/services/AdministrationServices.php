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
 * @version 2.22.12.07
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
            $orderBy = self::FIELD_NOMTITULAIRE;
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

    /**
     * @param AdministrationClass $obj
     * @since 2.22.12.07
     * @version 2.22.12.07
     */
    public function insert(&$obj)
    {
        $arrParams = array(
            $obj->getField(self::FIELD_GENRE),
            $obj->getField(self::FIELD_NOMTITULAIRE),
            $obj->getField(self::FIELD_LABELPOSTE),
        );
        $id = $this->objDao->insertAdministration($arrParams);
        $obj->setField(self::FIELD_ID, $id);
    }
    
    /**
     * @param AdministrationClass $obj
     * @since 2.22.12.07
     * @version 2.22.12.07
     */
    public function update($obj)
    {
        $arrParams = array(
            $obj->getField(self::FIELD_GENRE),
            $obj->getField(self::FIELD_NOMTITULAIRE),
            $obj->getField(self::FIELD_LABELPOSTE),
            $obj->getField(self::FIELD_ID),
        );
        $this->objDao->updateAdministration($arrParams);
    }
    
    /**
     * @param AdministrationClass $obj
     * @since 2.22.12.07
     * @version 2.22.12.07
     */
    public function delete($obj)
    {
        $arrParams = array(
            $obj->getField(self::FIELD_ID),
        );
        $this->objDao->deleteAdministration($arrParams);
    }
}
