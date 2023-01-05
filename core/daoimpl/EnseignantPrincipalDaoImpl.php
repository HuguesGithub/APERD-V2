<?php
namespace core\daoimpl;

use core\domain\EnseignantPrincipalClass;
use core\domain\MySQLClass;

if (!defined('ABSPATH')) {
    die('Forbidden');
}
/**
 * Classe EnseignantPrincipalDaoImpl
 * @author Hugues
 * @since v2.23.01.04
 * @version v2.23.01.04
 */
class EnseignantPrincipalDaoImpl extends LocalDaoImpl
{
    //////////////////////////////////////////////////
    // CONSTRUCT
    //////////////////////////////////////////////////
    
    /**
     * @since v2.23.01.04
     * @version v2.23.01.04
     */
    public function __construct()
    {
        $this->dbTable = self::DB_PREFIX.'enseignant_principal';
        $this->dbTable_enseignant = self::DB_PREFIX.'enseignant';
        $this->dbTable_division = self::DB_PREFIX.'division';
        parent::__construct();
    }
    
    /**
     * @param array $rows
     * @return array
     * @since v2.23.01.04
     * @version v2.23.01.04
     */
    protected function convertToArray($rows)
    {
        $objItems = array();
        while (!empty($rows)) {
            $row = array_shift($rows);
            $objItems[] = new EnseignantPrincipalClass($row);
        }
        return $objItems;
    }
    
    /**
     * @param array $attributes
     * @return array
     * @since v2.23.01.04
     * @version v2.23.01.04
     */
    public function getEnseignantPrincipalsWithFilters($attributes)
    {
        //////////////////////////////
        // Construction de la requête
        $request  = "SELECT ep.id AS id, divisionId, enseignantId FROM ".$this->dbTable." AS ep ";
        $request .= "INNER JOIN ".$this->dbTable_enseignant." AS e ON e.id=enseignantId ";
        $request .= "INNER JOIN ".$this->dbTable_division." AS d ON d.id=divisionId ";
        $request .= "WHERE 1=1 AND divisionId LIKE '%s' AND enseignantId LIKE '%s' ";
        return $this->getRequestWithFilters($request, $attributes);
    }
    
    /**
     * @param int $id
     * @return EnseignantPrincipalClass
     * @since v2.23.01.04
     * @version v2.23.01.04
     */
    public function getEnseignantPrincipalById($id)
    {
        //////////////////////////////
        // Construction de la requête
        $request  = "SELECT id, divisionId, enseignantId FROM ".$this->dbTable." ";
        $request .= "WHERE id='%s' ";
        $prepRequest = vsprintf($request, array($id));
        
        //////////////////////////////
        // Exécution de la requête
        $row = MySQLClass::wpdbSelect($prepRequest);
        return (empty($row) ? new EnseignantPrincipalClass() : new EnseignantPrincipalClass($row[0]));
        //////////////////////////////
    }
    
    /**
     * @param array $attributes
     * @return int
     * @since v2.23.01.04
     * @version v2.23.01.04
     */
    public function insertEnseignantPrincipal($attributes)
    {
        //////////////////////////////
        // Construction de la requête
        $request  = "INSERT INTO ".$this->dbTable." (divisionId, enseignantId) ";
        $request .= "VALUES ('%s', '%s');";
        return parent::insert($request, $attributes);
    }
    
    /**
     * @param array $attributes
     * @since v2.23.01.04
     * @version v2.23.01.04
     */
    public function updateEnseignantPrincipal($attributes)
    {
        //////////////////////////////
        // Construction de la requête
        $request  = "UPDATE ".$this->dbTable." SET ";
        $request .= "divisionId = '%s', ";
        $request .= "enseignantId = '%s' ";
        $request .= "WHERE id = '%s';";
        parent::update($request, $attributes);
    }
    
    /**
     * @param array $attributes
     * @since v2.23.01.04
     * @version v2.23.01.04
     */
    public function deleteEnseignantPrincipal($attributes)
    {
        //////////////////////////////
        // Construction de la requête
        $request  = "DELETE FROM ".$this->dbTable." ";
        $request .= "WHERE id = '%s';";
        parent::delete($request, $attributes);
    }
}
