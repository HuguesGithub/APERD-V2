<?php
namespace core\services;

use core\daoimpl\MatiereDaoImpl;
use core\domain\MatiereClass;

if (!defined('ABSPATH')) {
    die('Forbidden');
}
/**
 * Classe MatiereServices
 * @author Hugues
 * @since 2.22.12.21
 * @version 2.22.12.21
 */
class MatiereServices extends LocalServices
{
    //////////////////////////////////////////////////
    // CONSTRUCT
    //////////////////////////////////////////////////
    /**
     * Class constructor
     * @since 2.22.12.21
     * @version 2.22.12.21
     */
    public function __construct()
    { $this->objDao = new MatiereDaoImpl(); }

    //////////////////////////////////////////////////
    // METHODS
    //////////////////////////////////////////////////
    
    /**
     * @param array $arrFilters
     * @return array
     * @since 2.22.12.21
     * @version 2.22.12.21
     */
    public function buildFilters($arrFilters)
    {
        $arrParams = array();
        array_push($arrParams, $this->getValueToSearch($arrFilters, self::FIELD_LABELMATIERE));
        return $arrParams;
    }
    
    /**
     * @param MatiereClass $obj
     * @since 2.22.12.21
     * @version 2.22.12.21
     */
    public function delete($obj)
    { $this->objDao->deleteMatiere(array($obj->getField(self::FIELD_ID))); }
    
    /**
     * @param integer $id
     * @return MatiereClass
     * @since 2.22.12.21
     * @version 2.22.12.21
     */
    public function getMatiereById($id)
    { return $this->objDao->getMatiereById($id); }
    
    /**
     * @param array $arrFilters
     * @param string $orderby
     * @param string $order
     * @return array
     * @since 2.22.12.21
     * @version 2.22.12.21
     */
    public function getMatieresWithFilters(
        $arrFilters=array(),
        $orderBy=self::FIELD_LABELMATIERE,
        $order=self::SQL_ORDER_ASC
    ) {
        $arrParams = $this->initRequestParams($arrFilters, $orderBy, $order);
        return $this->objDao->getMatieresWithFilters($arrParams);
    }
    
    /**
     * @param MatiereClass $obj
     * @since 2.22.12.21
     * @version 2.22.12.21
     */
    public function insert(&$obj)
    {
        $id = $this->objDao->insertMatiere($this->getParamsForInsert($obj));
        $obj->setField(self::FIELD_ID, $id);
    }
    
    /**
     * @param MatiereClass $obj
     * @since 2.22.12.21
     * @version 2.22.12.21
     */
    public function update($obj)
    { $this->objDao->updateMatiere($this->getParamsForUpdate($obj)); }
}
