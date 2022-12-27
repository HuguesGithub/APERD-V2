<?php
namespace core\daoimpl;

use core\domain\EleveClass;
use core\domain\MySQLClass;

if (!defined('ABSPATH')) {
    die('Forbidden');
}
/**
 * Classe EleveDaoImpl
 * @author Hugues
 * @since 2.22.12.23
 * @version 2.22.12.23
 */
class EleveDaoImpl extends LocalDaoImpl
{
    //////////////////////////////////////////////////
    // CONSTRUCT
    //////////////////////////////////////////////////
    
    /**
     * @since 2.22.12.23
     * @version 2.22.12.23
     */
    public function __construct()
    {
        $this->dbTable = self::DB_PREFIX.'eleve';
        $this->select = "SELECT id, nomEleve, prenomEleve, divisionId, delegue FROM ";
        parent::__construct();
    }
    
    /**
     * @param array $rows
     * @return array
     * @since 2.22.12.23
     * @version 2.22.12.23
     */
    protected function convertToArray($rows)
    {
        $objItems = array();
        while (!empty($rows)) {
            $row = array_shift($rows);
            $objItems[] = new EleveClass($row);
        }
        return $objItems;
    }
    
    /**
     * @param array $attributes
     * @return array
     * @since 2.22.12.23
     * @version 2.22.12.23
     */
    public function getElevesWithFilters($attributes)
    {
        //////////////////////////////
        // Construction de la requête
        $request  = $this->select.$this->dbTable;
        $request .= " WHERE 1=1 AND nomEleve LIKE '%s' AND divisionId LIKE '%s' AND delegue LIKE '%s' ";
        return $this->getRequestWithFilters($request, $attributes);
    }
    
    /**
     * @param int $id
     * @return EleveClass
     * @since 2.22.12.23
     * @version 2.22.12.23
     */
    public function getEleveById($id)
    {
        //////////////////////////////
        // Construction de la requête
        $request  = $this->select.$this->dbTable;
        $request .= " WHERE id='%s' ";
        $prepRequest = vsprintf($request, array($id));
        
        //////////////////////////////
        // Exécution de la requête
        $row = MySQLClass::wpdbSelect($prepRequest);
        return (empty($row) ? new EleveClass() : new EleveClass($row[0]));
        //////////////////////////////
    }
    
    /**
     * @param array $attributes
     * @return int
     * @since 2.22.12.23
     * @version 2.22.12.23
     */
    public function insertEleve($attributes)
    {
        //////////////////////////////
        // Construction de la requête
        $request  = "INSERT INTO ".$this->dbTable." (nomEleve, prenomEleve, divisionId, delegue) ";
        $request .= "VALUES ('%s', '%s', '%s', '%s');";
        return parent::insert($request, $attributes);
    }
    
    /**
     * @param array $attributes
     * @since 2.22.12.23
     * @version 2.22.12.23
     */
    public function updateEleve($attributes)
    {
        //////////////////////////////
        // Construction de la requête
        $request  = "UPDATE ".$this->dbTable." SET ";
        $request .= "nomEleve = '%s', ";
        $request .= "prenomEleve = '%s', ";
        $request .= "divisionId = '%s', ";
        $request .= "delegue = '%s' ";
        $request .= "WHERE id = '%s';";
        parent::update($request, $attributes);
    }
    
    /**
     * @param array $attributes
     * @since 2.22.12.23
     * @version 2.22.12.23
     */
    public function deleteEleve($attributes)
    {
        //////////////////////////////
        // Construction de la requête
        $request  = "DELETE FROM ".$this->dbTable." ";
        $request .= "WHERE id = '%s';";
        parent::delete($request, $attributes);
    }
}
