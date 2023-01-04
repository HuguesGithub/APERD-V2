<?php
namespace core\services;

use core\daoimpl\EnseignantPrincipalDaoImpl;
use core\domain\EnseignantPrincipalClass;

if (!defined('ABSPATH')) {
    die('Forbidden');
}
/**
 * Classe EnseignantPrincipalServices
 * @author Hugues
 * @since v2.23.01.04
 * @version v2.23.01.04
 */
class EnseignantPrincipalServices extends LocalServices
{
    //////////////////////////////////////////////////
    // CONSTRUCT
    //////////////////////////////////////////////////
    /**
     * Class constructor
     * @since v2.23.01.04
     * @version v2.23.01.04
     */
    public function __construct()
    { $this->objDao = new EnseignantPrincipalDaoImpl(); }

    //////////////////////////////////////////////////
    // METHODS
    //////////////////////////////////////////////////
    
    /**
     * @param array $arrFilters
     * @return array
     * @since v2.23.01.04
     * @version v2.23.01.04
     */
    public function buildFilters($arrFilters)
    {
        $arrParams = array();
        array_push($arrParams, $this->getValueToSearch($arrFilters, self::FIELD_DIVISIONID));
        array_push($arrParams, $this->getValueToSearch($arrFilters, self::FIELD_ENSEIGNANTID));
        return $arrParams;
    }
    
    /**
     * @param EnseignantPrincipalClass $obj
     * @since v2.23.01.04
     * @version v2.23.01.04
     */
    public function delete($obj)
    { $this->objDao->deleteEnseignantPrincipal(array($obj->getField(self::FIELD_ID))); }
    
    /**
     * @param integer $id
     * @return EnseignantPrincipalClass
     * @since v2.23.01.04
     * @version v2.23.01.04
     */
    public function getEnseignantPrincipalById($id)
    { return $this->objDao->getEnseignantPrincipalById($id); }
    
    /**
     * @param array $arrFilters
     * @param string $orderby
     * @param string $order
     * @return array
     * @since v2.23.01.04
     * @version v2.23.01.04
     */
    public function getEnseignantPrincipalsWithFilters(
        $arrFilters=array(),
        $orderBy=self::FIELD_LABELDIVISION,
        $order=self::SQL_ORDER_ASC
    ) {
        $arrParams = $this->initRequestParams($arrFilters, $orderBy, $order);
        return $this->objDao->getEnseignantPrincipalsWithFilters($arrParams);
    }
    
    /**
     * @param EnseignantPrincipalClass $obj
     * @since v2.23.01.04
     * @version v2.23.01.04
     */
    public function insert(&$obj)
    {
        $id = $this->objDao->insertEnseignantPrincipal($this->getParamsForInsert($obj));
        $obj->setField(self::FIELD_ID, $id);
    }
    
    /**
     * @param EnseignantPrincipalClass $obj
     * @since v2.23.01.04
     * @version v2.23.01.04
     */
    public function update($obj)
    { $this->objDao->updateEnseignantPrincipal($this->getParamsForUpdate($obj)); }
}
