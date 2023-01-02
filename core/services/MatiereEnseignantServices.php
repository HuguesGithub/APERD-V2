<?php
namespace core\services;

use core\daoimpl\MatiereEnseignantDaoImpl;
use core\domain\MatiereEnseignantClass;

if (!defined('ABSPATH')) {
    die('Forbidden');
}
/**
 * Classe MatiereEnseignantServices
 * @author Hugues
 * @since v2.23.01.02
 * @version v2.23.01.02
 */
class MatiereEnseignantServices extends LocalServices
{
    //////////////////////////////////////////////////
    // CONSTRUCT
    //////////////////////////////////////////////////
    /**
     * Class constructor
     * @since v2.23.0.02
     * @version v2.23.01.02
     */
    public function __construct()
    { $this->objDao = new MatiereEnseignantDaoImpl(); }

    //////////////////////////////////////////////////
    // METHODS
    //////////////////////////////////////////////////
    
    /**
     * @param array $arrFilters
     * @return array
     * @since v2.23.01.02
     * @version v2.23.01.02
     */
    public function buildFilters($arrFilters)
    {
        $arrParams = array();
        array_push($arrParams, $this->getValueToSearch($arrFilters, self::FIELD_MATIEREID));
        array_push($arrParams, $this->getValueToSearch($arrFilters, self::FIELD_ENSEIGNANTID));
        return $arrParams;
    }
    
    /**
     * @param MatiereEnseignantClass $obj
     * @since v2.23.01.02
     * @version v2.23.01.02
     */
    public function delete($obj)
    { $this->objDao->deleteMatiereEnseignant(array($obj->getField(self::FIELD_ID))); }
    
    /**
     * @param integer $id
     * @return MatiereEnseignantClass
     * @since v2.23.01.02
     * @version v2.23.01.02
     */
    public function getMatiereEnseignantById($id)
    { return $this->objDao->getMatiereEnseignantById($id); }
    
    /**
     * @param array $arrFilters
     * @param string $orderby
     * @param string $order
     * @return array
     * @since v2.23.01.02
     * @version v2.23.01.02
     */
    public function getMatiereEnseignantsWithFilters(
        $arrFilters=array(),
        $orderBy=self::FIELD_LABELMATIERE,
        $order=self::SQL_ORDER_ASC
    ) {
        $arrParams = $this->initRequestParams($arrFilters, $orderBy, $order);
        return $this->objDao->getMatiereEnseignantsWithFilters($arrParams);
    }
    
    /**
     * @param MatiereEnseignantClass $obj
     * @since v2.23.01.02
     * @version v2.23.01.02
     */
    public function insert(&$obj)
    {
        $id = $this->objDao->insertMatiereEnseignant($this->getParamsForInsert($obj));
        $obj->setField(self::FIELD_ID, $id);
    }
    
    /**
     * @param MatiereEnseignantClass $obj
     * @since v2.23.01.02
     * @version v2.23.01.02
     */
    public function update($obj)
    { $this->objDao->updateMatiereEnseignant($this->getParamsForUpdate($obj)); }
}
