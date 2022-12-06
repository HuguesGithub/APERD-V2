<?php
namespace core\daoimpl;
use core\domain\AdministrationClass;
use core\domain\MySQL;

if (!defined('ABSPATH')) {
    die('Forbidden');
}
/**
 * Classe AdministrationDaoImpl
 * @author Hugues
 * @since 2.22.12.05
 * @version 2.22.12.06
 */
class AdministrationDaoImpl extends LocalDaoImpl
{
    //////////////////////////////////////////////////
    // CONSTRUCT
    //////////////////////////////////////////////////
    
    /**
     * @since 2.22.12.05
     * @version 2.22.12.05
     */
    public function __construct()
    {
        $this->obj = new AdministrationClass();
        $this->dbTable = 'wp_14_aperd_administration';
        parent::__construct();
    }
    
    /**
     * @param array $rows
     * @return array
     * @since 2.22.12.05
     * @version 2.22.12.05
     */
    protected function convertToArray($rows)
    {
        $objItems = array();
        while (!empty($rows)) {
            $row = array_shift($rows);
            $objItems[] = new AdministrationClass($row);
        }
        return $objItems;
    }
    
    /**
     * @param array $attributes
     * @return array
     * @since 2.22.12.05
     * @version 2.22.12.06
     */
    public function getAdministrationsWithFilters($attributes)
    {
        //////////////////////////////
        // Construction de la requête
        $request  = "SELECT id, genre, nomTitulaire, labelPoste FROM ".$this->dbTable." ";
        $request .= "WHERE 1=1 AND nomTitulaire LIKE '%s' AND labelPoste LIKE '%s' ";
        return $this->getRequestWithFilters($request, $attributes);
    }
    
    /**
     * @param int $adminId
     * @return AdministrationClass
     * @since 2.22.12.05
     * @version 2.22.12.05
     */
    public function getAdministrationById($adminId)
    {
        //////////////////////////////
        // Construction de la requête
        $request  = "SELECT id, genre, nomTitulaire, labelPoste FROM ".$this->dbTable." ";
        $request .= "WHERE id='%s' ";
        
        $prepRequest = vsprintf($request, array($adminId));
        
        //////////////////////////////
        // Exécution de la requête
        $row = MySQL::wpdbSelect($prepRequest);
        return (empty($row) ? new AdministrationClass() : new AdministrationClass($row[0]));
        //////////////////////////////
    }
}
