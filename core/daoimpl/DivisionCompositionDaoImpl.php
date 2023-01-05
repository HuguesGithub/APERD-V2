<?php
namespace core\daoimpl;

use core\domain\DivisionCompositionClass;
use core\domain\MySQLClass;

if (!defined('ABSPATH')) {
    die('Forbidden');
}
/**
 * Classe DivisionCompositionDaoImpl
 * @author Hugues
 * @since v2.23.01.03
 * @version v2.23.01.03
 */
class DivisionCompositionDaoImpl extends LocalDaoImpl
{
    //////////////////////////////////////////////////
    // CONSTRUCT
    //////////////////////////////////////////////////
    
    /**
     * @since v2.23.01.03
     * @version v2.23.01.03
     */
    public function __construct()
    {
        $this->dbTable = self::DB_PREFIX.'division_composition';
        $this->dbTable_mat_ens = self::DB_PREFIX.'matiere_enseignant';
        $this->dbTable_division = self::DB_PREFIX.'division';
        $this->dbTable_matiere = self::DB_PREFIX.'matiere';
        $this->dbTable_enseignant = self::DB_PREFIX.'enseignant';
        parent::__construct();
    }
    
    /**
     * @param array $rows
     * @return array
     * @since v2.23.01.03
     * @version v2.23.01.03
     */
    protected function convertToArray($rows)
    {
        $objItems = array();
        while (!empty($rows)) {
            $row = array_shift($rows);
            $objItems[] = new DivisionCompositionClass($row);
        }
        return $objItems;
    }
    
    /**
     * @param array $attributes
     * @return array
     * @since v2.23.01.03
     * @version v2.23.01.03
     */
    public function getDivisionCompositionsWithFilters($attributes)
    {
        //////////////////////////////
        // Construction de la requête
        $request  = "SELECT dc.id AS id, divisionId, matiereEnseignantId FROM ".$this->dbTable." AS dc ";
        $request .= "INNER JOIN ".$this->dbTable_division." AS d ON d.id=divisionId ";
        $request .= "INNER JOIN ".$this->dbTable_mat_ens." AS me ON me.id=matiereEnseignantId ";
        $request .= "INNER JOIN ".$this->dbTable_matiere." AS m ON m.id=matiereId ";
        $request .= "INNER JOIN ".$this->dbTable_enseignant." AS e ON e.id=enseignantId ";
        $request .= "WHERE 1=1 AND divisionId LIKE '%s' ";
        return $this->getRequestWithFilters($request, $attributes);
    }
    
    /**
     * @param int $id
     * @return DivisionCompositionClass
     * @since v2.23.01.03
     * @version v2.23.01.03
     */
    public function getDivisionCompositionById($id)
    {
        //////////////////////////////
        // Construction de la requête
        $request  = "SELECT id, divisionId, matiereEnseignantId FROM ".$this->dbTable." ";
        $request .= "WHERE id='%s' ";
        $prepRequest = vsprintf($request, array($id));
        
        //////////////////////////////
        // Exécution de la requête
        $row = MySQLClass::wpdbSelect($prepRequest);
        return (empty($row) ? new DivisionCompositionClass() : new DivisionCompositionClass($row[0]));
        //////////////////////////////
    }
    
    /**
     * @param array $attributes
     * @return int
     * @since v2.23.01.03
     * @version v2.23.01.03
     */
    public function insertDivisionComposition($attributes)
    {
        //////////////////////////////
        // Construction de la requête
        $request  = "INSERT INTO ".$this->dbTable." (divisionId, matiereEnseignantId) ";
        $request .= "VALUES ('%s', '%s');";
        return parent::insert($request, $attributes);
    }
    
    /**
     * @param array $attributes
     * @since v2.23.01.03
     * @version v2.23.01.03
     */
    public function updateDivisionComposition($attributes)
    {
        //////////////////////////////
        // Construction de la requête
        $request  = "UPDATE ".$this->dbTable." SET ";
        $request .= "divisionId = '%s', ";
        $request .= "matiereEnseignantId = '%s' ";
        $request .= "WHERE id = '%s';";
        parent::update($request, $attributes);
    }
    
    /**
     * @param array $attributes
     * @since v2.23.01.03
     * @version v2.23.01.03
     */
    public function deleteDivisionComposition($attributes)
    {
        //////////////////////////////
        // Construction de la requête
        $request  = "DELETE FROM ".$this->dbTable." ";
        $request .= "WHERE id = '%s';";
        parent::delete($request, $attributes);
    }
}