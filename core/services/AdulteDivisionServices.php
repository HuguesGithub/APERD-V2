<?php
namespace core\services;

use core\daoimpl\AdulteDivisionDaoImpl;
use core\domain\AdulteDivisionClass;

if (!defined('ABSPATH')) {
    die('Forbidden');
}
/**
 * Classe AdulteDivisionServices
 * @author Hugues
 * @since 2.22.12.12
 * @version 2.22.12.12
 */
class AdulteDivisionServices extends LocalServices
{
    //////////////////////////////////////////////////
    // CONSTRUCT
    //////////////////////////////////////////////////
    /**
     * Class constructor
     * @since 2.22.12.12
     * @version 2.22.12.12
     */
    public function __construct()
    { $this->objDao = new AdulteDivisionDaoImpl(); }

    //////////////////////////////////////////////////
    // METHODS
    //////////////////////////////////////////////////
    
    /**
     * @param array $arrFilters
     * @return array
     * @since 2.22.12.12
     * @version 2.22.12.12
     */
    public function buildFilters($arrFilters)
    {
        $arrParams = array();
        array_push($arrParams, $this->getValueToSearch($arrFilters, self::FIELD_ADULTEID));
        array_push($arrParams, $this->getValueToSearch($arrFilters, self::FIELD_DIVISIONID));
        array_push($arrParams, $this->getValueToSearch($arrFilters, self::FIELD_DELEGUE));
        array_push($arrParams, $this->getValueToSearch($arrFilters, self::FIELD_ADHERENT));
        return $arrParams;
    }
    
    /**
     * @param AdulteDivisionClass $obj
     * @since 2.22.12.12
     * @version 2.22.12.12
     */
    public function delete($obj)
    { $this->objDao->deleteAdulteDivision(array($obj->getField(self::FIELD_ID))); }
    
    /**
     * @param integer $id
     * @return AdulteDivisionClass
     * @since 2.22.12.12
     * @version 2.22.12.12
     */
    public function getAdulteDivisionById($id)
    { return $this->objDao->getAdulteDivisionById($id); }
    
    /**
     * @param array $arrFilters
     * @param string $orderby
     * @param string $order
     * @return array
     * @since 2.22.12.12
     * @version 2.22.12.12
     */
    public function getAdulteDivisionsWithFilters(
        $arrFilters=array(),
        $orderBy=self::FIELD_LABELDIVISION,
        $order=self::SQL_ORDER_ASC
    ) {
        $arrParams = $this->initRequestParams($arrFilters, $orderBy, $order);
        return $this->objDao->getAdulteDivisionsWithFilters($arrParams);
    }
    
    /**
     * @param AdulteDivisionClass $obj
     * @since 2.22.12.12
     * @version 2.22.12.12
     */
    public function insert(&$obj)
    {
        $id = $this->objDao->insertAdulteDivision($this->getParamsForInsert($obj));
        $obj->setField(self::FIELD_ID, $id);
    }
    
    /**
     * @param AdulteDivisionClass $obj
     * @since 2.22.12.12
     * @version 2.22.12.12
     */
    public function update($obj)
    { $this->objDao->updateAdulteDivision($this->getParamsForUpdate($obj)); }
}
