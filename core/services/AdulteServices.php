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
    {
        $this->objDao = new AdulteDaoImpl();
    }

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
     * @param array $arrFilters
     * @param string $orderby
     * @param string $order
     * @return array
     * @since 2.22.12.08
     * @version 2.22.12.08
     */
    public function getAdultesWithFilters($arrFilters=array(), $orderBy='', $order='')
    {
        if ($orderBy=='') {
            $orderBy = self::FIELD_NOMADULTE;
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
        return $this->objDao->getAdultesWithFilters($arrParams);
    }
    
    /**
     * @param integer $adulteId
     * @return AdulteClass
     * @since 2.22.12.08
     * @version 2.22.12.08
     */
    public function getAdulteById($adulteId)
    { return $this->objDao->getAdulteById($adulteId); }

    /**
     * @param AdulteClass $obj
     * @since 2.22.12.08
     * @version 2.22.12.08
     */
    public function insert(&$obj)
    {
        $arrParams = array(
            $obj->getField(self::FIELD_NOMADULTE),
            $obj->getField(self::FIELD_PRENOMADULTE),
            $obj->getField(self::FIELD_MAILADULTE),
            $obj->getField(self::FIELD_ADHERENT),
        );
        $id = $this->objDao->insertAdulte($arrParams);
        $obj->setField(self::FIELD_ID, $id);
    }
    
    /**
     * @param AdulteClass $obj
     * @since 2.22.12.08
     * @version 2.22.12.08
     */
    public function update($obj)
    {
        $arrParams = array(
            $obj->getField(self::FIELD_NOMADULTE),
            $obj->getField(self::FIELD_PRENOMADULTE),
            $obj->getField(self::FIELD_MAILADULTE),
            $obj->getField(self::FIELD_ADHERENT),
            $obj->getField(self::FIELD_ID),
        );
        $this->objDao->updateAdulte($arrParams);
    }
    
    /**
     * @param AdulteClass $obj
     * @since 2.22.12.08
     * @version 2.22.12.08
     */
    public function delete($obj)
    {
        $arrParams = array(
            $obj->getField(self::FIELD_ID),
        );
        $this->objDao->deleteAdulte($arrParams);
    }
}
