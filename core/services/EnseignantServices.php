<?php
namespace core\services;

use core\daoimpl\EnseignantDaoImpl;
use core\domain\EnseignantClass;

if (!defined('ABSPATH')) {
    die('Forbidden');
}
/**
 * Classe EnseignantServices
 * @author Hugues
 * @since v2.23.01.02
 * @version v2.23.01.02
 */
class EnseignantServices extends LocalServices
{
    //////////////////////////////////////////////////
    // CONSTRUCT
    //////////////////////////////////////////////////
    /**
     * Class constructor
     * @since v2.23.01.02
     * @version v2.23.01.02
     */
    public function __construct()
    { $this->objDao = new EnseignantDaoImpl(); }

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
        array_push($arrParams, $this->getValueToSearch($arrFilters, self::FIELD_NOMENSEIGNANT));
        return $arrParams;
    }
    
    /**
     * @param EnseignantClass $obj
     * @since v2.23.01.02
     * @version v2.23.01.02
     */
    public function delete($obj)
    { $this->objDao->deleteEnseignant(array($obj->getField(self::FIELD_ID))); }
    
    /**
     * @param integer $adulteId
     * @return EnseignantClass
     * @since v2.23.01.02
     * @version v2.23.01.02
     */
    public function getEnseignantById($id)
    { return $this->objDao->getEnseignantById($id); }
    
    /**
     * @param array $arrFilters
     * @param string $orderby
     * @param string $order
     * @return array
     * @since v2.23.01.02
     * @version v2.23.01.02
     */
    public function getEnseignantsWithFilters(
        $arrFilters=array(),
        $orderBy=self::FIELD_NOMENSEIGNANT,
        $order=self::SQL_ORDER_ASC
    ) {
        $arrParams = $this->initRequestParams($arrFilters, $orderBy, $order);
        return $this->objDao->getEnseignantsWithFilters($arrParams);
    }
    
    /**
     * On recherche un Enseignant dont le nom + prenom correspond au paramètre
     * @param string $nomPrenomEnseignant
     * @return \core\domain\EnseignantClass
     * @since v2.23.01.02
     * @version v2.23.01.02
     */
    public function getEnseignantByNomPrenom($nomPrenomEnseignant)
    { return $this->objDao->getEnseignantByNomPrenom($nomPrenomEnseignant); }
    
    /**
     * @param EnseignantClass $obj
     * @since v2.23.01.02
     * @version v2.23.01.02
     */
    public function insert(&$obj)
    {
        $id = $this->objDao->insertEnseignant($this->getParamsForInsert($obj));
        $obj->setField(self::FIELD_ID, $id);
    }
    
    /**
     * @param EnseignantClass $obj
     * @since v2.23.01.02
     * @version v2.23.01.02
     */
    public function update($obj)
    { $this->objDao->updateEnseignant($this->getParamsForUpdate($obj)); }
}
