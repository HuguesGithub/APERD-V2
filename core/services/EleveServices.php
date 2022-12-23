<?php
namespace core\services;

use core\daoimpl\EleveDaoImpl;
use core\domain\EleveClass;

if (!defined('ABSPATH')) {
    die('Forbidden');
}
/**
 * Classe EleveServices
 * @author Hugues
 * @since 2.22.12.23
 * @version 2.22.12.23
 */
class EleveServices extends LocalServices
{
    //////////////////////////////////////////////////
    // CONSTRUCT
    //////////////////////////////////////////////////
    /**
     * Class constructor
     * @since 2.22.12.23
     * @version 2.22.12.23
     */
    public function __construct()
    { $this->objDao = new EleveDaoImpl(); }

    //////////////////////////////////////////////////
    // METHODS
    //////////////////////////////////////////////////
    
    /**
     * @param array $arrFilters
     * @return array
     * @since 2.22.12.23
     * @version 2.22.12.23
     */
    public function buildFilters($arrFilters)
    {
        $arrParams = array();
        array_push($arrParams, $this->getValueToSearch($arrFilters, self::FIELD_NOMELEVE));
        array_push($arrParams, $this->getValueToSearch($arrFilters, self::FIELD_DIVISIONID));
        array_push($arrParams, $this->getValueToSearch($arrFilters, self::FIELD_DELEGUE));
        return $arrParams;
    }
    
    /**
     * @param EleveClass $obj
     * @since 2.22.12.23
     * @version 2.22.12.23
     */
    public function delete($obj)
    { $this->objDao->deleteEleve(array($obj->getField(self::FIELD_ID))); }
    
    /**
     * @param integer $id
     * @return EleveClass
     * @since 2.22.12.23
     * @version 2.22.12.23
     */
    public function getEleveById($id)
    { return $this->objDao->getEleveById($id); }
    
    /**
     * @param array $arrFilters
     * @param string $orderby
     * @param string $order
     * @return array
     * @since 2.22.12.23
     * @version 2.22.12.23
     */
    public function getElevesWithFilters(
        $arrFilters=array(),
        $orderBy=self::FIELD_NOMELEVE,
        $order=self::SQL_ORDER_ASC
    ) {
        $arrParams = $this->initRequestParams($arrFilters, $orderBy, $order);
        return $this->objDao->getElevesWithFilters($arrParams);
    }
    
    /**
     * @param EleveClass $obj
     * @since 2.22.12.23
     * @version 2.22.12.23
     */
    public function insert(&$obj)
    {
        $id = $this->objDao->insertEleve($this->getParamsForInsert($obj));
        $obj->setField(self::FIELD_ID, $id);
    }
    
    /**
     * @param EleveClass $obj
     * @since 2.22.12.23
     * @version 2.22.12.23
     */
    public function update($obj)
    { $this->objDao->updateEleve($this->getParamsForUpdate($obj)); }
}
