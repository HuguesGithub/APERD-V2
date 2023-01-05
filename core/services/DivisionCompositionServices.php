<?php
namespace core\services;

use core\daoimpl\DivisionCompositionDaoImpl;
use core\domain\DivisionCompositionClass;

if (!defined('ABSPATH')) {
    die('Forbidden');
}
/**
 * Classe DivisionCompositionServices
 * @author Hugues
 * @since v2.23.01.03
 * @version v2.23.01.03
 */
class DivisionCompositionServices extends LocalServices
{
    //////////////////////////////////////////////////
    // CONSTRUCT
    //////////////////////////////////////////////////
    /**
     * Class constructor
     * @since v2.23.01.03
     * @version v2.23.01.03
     */
    public function __construct()
    { $this->objDao = new DivisionCompositionDaoImpl(); }

    //////////////////////////////////////////////////
    // METHODS
    //////////////////////////////////////////////////
    
    /**
     * @param array $arrFilters
     * @return array
     * @since v2.23.01.03
     * @version v2.23.01.03
     */
    public function buildFilters($arrFilters)
    {
        $arrParams = array();
        array_push($arrParams, $this->getValueToSearch($arrFilters, self::FIELD_DIVISIONID));
        return $arrParams;
    }
    
    /**
     * @param DivisionCompositionClass $obj
     * @since v2.23.01.03
     * @version v2.23.01.03
     */
    public function delete($obj)
    { $this->objDao->deleteDivisionComposition(array($obj->getField(self::FIELD_ID))); }
    
    /**
     * @param integer $id
     * @return DivisionCompositionClass
     * @since v2.23.01.03
     * @version v2.23.01.03
     */
    public function getDivisionCompositionById($id)
    { return $this->objDao->getDivisionCompositionById($id); }
    
    /**
     * @param array $arrFilters
     * @param string $orderby
     * @param string $order
     * @return array
     * @since v2.23.01.03
     * @version v2.23.01.03
     */
    public function getDivisionCompositionsWithFilters(
        $arrFilters=array(),
        $orderBy=array(self::FIELD_LABELDIVISION, self::FIELD_LABELMATIERE),
        $order=array(self::SQL_ORDER_ASC, self::SQL_ORDER_ASC)
    ) {
        $arrParams = $this->initRequestParams($arrFilters, $orderBy, $order);
        return $this->objDao->getDivisionCompositionsWithFilters($arrParams);
    }
    
    /**
     * @param DivisionCompositionClass $obj
     * @since v2.23.01.03
     * @version v2.23.01.03
     */
    public function insert(&$obj)
    {
        $id = $this->objDao->insertDivisionComposition($this->getParamsForInsert($obj));
        $obj->setField(self::FIELD_ID, $id);
    }
    
    /**
     * @param DivisionCompositionClass $obj
     * @since v2.23.01.03
     * @version v2.23.01.03
     */
    public function update($obj)
    { $this->objDao->updateDivisionComposition($this->getParamsForUpdate($obj)); }
}