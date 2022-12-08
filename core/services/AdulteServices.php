<?php
namespace core\services;

use core\daoimpl\AdulteDaoImpl;
use core\domain\AdulteClass;

if (!defined('ABSPATH')) {
    die('Forbidden');
}
/**
 * Classe AdulteServices
 * @author Hugues
 * @since 2.22.12.08
 * @version 2.22.12.08
 */
class AdulteServices extends LocalServices
{
    //////////////////////////////////////////////////
    // CONSTRUCT
    //////////////////////////////////////////////////
    /**
     * Class constructor
     * @since 2.22.12.08
     * @version 2.22.12.08
     */
    public function __construct()
    { $this->objDao = new AdulteDaoImpl(); }

    //////////////////////////////////////////////////
    // METHODS
    //////////////////////////////////////////////////
    
    /**
     * @param array $arrFilters
     * @return array
     * @since 2.22.12.08
     * @version 2.22.12.08
     */
    private function buildFilters($arrFilters)
    {
        $arrParams = array();
        array_push($arrParams, $this->getValueToSearch($arrFilters, self::FIELD_NOMADULTE));
        array_push($arrParams, $this->getValueToSearch($arrFilters, self::FIELD_MAILADULTE));
        array_push($arrParams, $this->getValueToSearch($arrFilters, self::FIELD_ADHERENT));
        return $arrParams;
    }
    
    /**
     * @param AdulteClass $obj
     * @since 2.22.12.08
     * @version 2.22.12.08
     */
    public function delete($obj)
    { $this->objDao->deleteAdulte(array($obj->getField(self::FIELD_ID))); }
    
    /**
     * @param integer $adulteId
     * @return AdulteClass
     * @since 2.22.12.08
     * @version 2.22.12.08
     */
    public function getAdulteById($adulteId)
    { return $this->objDao->getAdulteById($adulteId); }
    
    /**
     * @param array $arrFilters
     * @param string $orderby
     * @param string $order
     * @return array
     * @since 2.22.12.08
     * @version 2.22.12.08
     */
    public function getAdultesWithFilters($arrFilters=array(), $orderBy=self::FIELD_NOMADULTE,
        $order=self::SQL_ORDER_ASC)
    {
        $arrParams = $this->initRequestParams($arrFilters, $orderBy, $order);
        return $this->objDao->getAdultesWithFilters($arrParams);
    }
    
    /**
     * @param AdulteClass $obj
     * @since 2.22.12.08
     * @version 2.22.12.08
     */
    public function insert(&$obj)
    {
        $id = $this->objDao->insertAdulte($this->getParamsForInsert($obj));
        $obj->setField(self::FIELD_ID, $id);
    }
    
    /**
     * @param AdulteClass $obj
     * @since 2.22.12.08
     * @version 2.22.12.08
     */
    public function update($obj)
    { $this->objDao->updateAdulte($this->getParamsForUpdate($obj)); }
}
