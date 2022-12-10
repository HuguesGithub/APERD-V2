<?php
namespace core\services;

use core\daoimpl\DivisionDaoImpl;
use core\domain\DivisionClass;

if (!defined('ABSPATH')) {
    die('Forbidden');
}
/**
 * Classe DivisionServices
 * @author Hugues
 * @since 2.22.12.11
 * @version 2.22.12.11
 */
class DivisionServices extends LocalServices
{
    //////////////////////////////////////////////////
    // CONSTRUCT
    //////////////////////////////////////////////////
    /**
     * Class constructor
     * @since 2.22.12.11
     * @version 2.22.12.11
     */
    public function __construct()
    { $this->objDao = new DivisionDaoImpl(); }

    //////////////////////////////////////////////////
    // METHODS
    //////////////////////////////////////////////////
    
    /**
     * @param array $arrFilters
     * @return array
     * @since 2.22.12.11
     * @version 2.22.12.11
     */
    public function buildFilters($arrFilters)
    {
        $arrParams = array();
        array_push($arrParams, $this->getValueToSearch($arrFilters, self::FIELD_LABELDIVISION));
        return $arrParams;
    }
    
    /**
     * @param DivisionClass $obj
     * @since 2.22.12.11
     * @version 2.22.12.11
     */
    public function delete($obj)
    { $this->objDao->deleteDivision(array($obj->getField(self::FIELD_ID))); }
    
    /**
     * @param integer $id
     * @return DivisionClass
     * @since 2.22.12.11
     * @version 2.22.12.11
     */
    public function getDivisionById($id)
    { return $this->objDao->getDivisionById($id); }
    
    /**
     * @param array $arrFilters
     * @param string $orderby
     * @param string $order
     * @return array
     * @since 2.22.12.11
     * @version 2.22.12.11
     */
    public function getDivisionsWithFilters(
        $arrFilters=array(),
        $orderBy=self::FIELD_LABELDIVISION,
        $order=self::SQL_ORDER_ASC
    ) {
        $arrParams = $this->initRequestParams($arrFilters, $orderBy, $order);
        return $this->objDao->getDivisionsWithFilters($arrParams);
    }
    
    /**
     * @param DivisionClass $obj
     * @since 2.22.12.11
     * @version 2.22.12.11
     */
    public function insert(&$obj)
    {
        $id = $this->objDao->insertDivision($this->getParamsForInsert($obj));
        $obj->setField(self::FIELD_ID, $id);
    }
    
    /**
     * @param DivisionClass $obj
     * @since 2.22.12.11
     * @version 2.22.12.11
     */
    public function update($obj)
    { $this->objDao->updateDivision($this->getParamsForUpdate($obj)); }
}
