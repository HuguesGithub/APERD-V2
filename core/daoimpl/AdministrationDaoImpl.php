<?php
namespace core\daoimpl;

use core\domain\AdministrationClass;
use core\domain\MySQLClass;

if (!defined('ABSPATH')) {
    die('Forbidden');
}
/**
 * Classe AdministrationDaoImpl
 * @author Hugues
 * @since 2.22.12.05
 * @version 2.22.12.07
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
        $this->dbTable = self::DB_PREFIX.'administration';
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
     * @param int $id
     * @return AdministrationClass
     * @since 2.22.12.05
     * @version 2.22.12.05
     */
    public function getAdministrationById($id)
    {
        //////////////////////////////
        // Construction de la requête
        $request  = "SELECT id, genre, nomTitulaire, labelPoste FROM ".$this->dbTable." ";
        $request .= "WHERE id='%s' ";
        $prepRequest = MySQLClass::wpdbPrepare($request, array($id));
        
        //////////////////////////////
        // Exécution de la requête
        $row = MySQLClass::wpdbSelect($prepRequest);
        return (empty($row) ? new AdministrationClass() : new AdministrationClass($row[0]));
        //////////////////////////////
    }
    
    /**
     * @param array $attributes
     * @return int
     * @since 2.22.12.07
     * @version 2.22.12.07
     */
    public function insertAdministration($attributes)
    {
        //////////////////////////////
        // Construction de la requête
        $request  = "INSERT INTO ".$this->dbTable." (genre, nomTitulaire, labelPoste) ";
        $request .= "VALUES ('%s', '%s', '%s');";
        return parent::insert($request, $attributes);
    }
    
    /**
     * @param array $attributes
     * @since 2.22.12.07
     * @version 2.22.12.07
     */
    public function updateAdministration($attributes)
    {
        //////////////////////////////
        // Construction de la requête
        $request  = "UPDATE ".$this->dbTable." SET ";
        $request .= "genre = '%s', ";
        $request .= "nomTitulaire = '%s', ";
        $request .= "labelPoste = '%s' ";
        $request .= "WHERE id = '%s';";
        parent::update($request, $attributes);
    }
    
    /**
     * @param array $attributes
     * @since 2.22.12.07
     * @version 2.22.12.07
     */
    public function deleteAdministration($attributes)
    {
        //////////////////////////////
        // Construction de la requête
        $request  = "DELETE FROM ".$this->dbTable." ";
        $request .= "WHERE id = '%s';";
        parent::delete($request, $attributes);
    }
}
