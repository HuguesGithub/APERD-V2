<?php
namespace core\daoimpl;

use core\domain\MatiereEnseignantClass;
use core\domain\MySQLClass;

if (!defined('ABSPATH')) {
    die('Forbidden');
}
/**
 * Classe MatiereEnseignantDaoImpl
 * @author Hugues
 * @since v2.23.01.02
 * @version v2.23.01.02
 */
class MatiereEnseignantDaoImpl extends LocalDaoImpl
{
    //////////////////////////////////////////////////
    // CONSTRUCT
    //////////////////////////////////////////////////
    
    /**
     * @since v2.23.01.02
     * @version v2.23.01.02
     */
    public function __construct()
    {
        $this->dbTable = self::DB_PREFIX.'matiere_enseignant';
        $this->dbTable_matiere = self::DB_PREFIX.'matiere';
        $this->dbTable_enseignant = self::DB_PREFIX.'enseignant';
        parent::__construct();
    }
    
    /**
     * @param array $rows
     * @return array
     * @since v2.23.01.02
     * @version v2.23.01.02
     */
    protected function convertToArray($rows)
    {
        $objItems = array();
        while (!empty($rows)) {
            $row = array_shift($rows);
            $objItems[] = new MatiereEnseignantClass($row);
        }
        return $objItems;
    }
    
    /**
     * @param array $attributes
     * @return array
     * @since v2.23.01.02
     * @version v2.23.01.02
     */
    public function getMatiereEnseignantsWithFilters($attributes)
    {
        //////////////////////////////
        // Construction de la requête
        $request  = "SELECT ae.id AS id, matiereId, enseignantId FROM ".$this->dbTable." AS ae ";
        $request .= "INNER JOIN ".$this->dbTable_matiere." AS a ON a.id=matiereId ";
        $request .= "INNER JOIN ".$this->dbTable_enseignant." AS d ON d.id=enseignantId ";
        $request .= "WHERE 1=1 AND matiereId LIKE '%s' AND enseignantId LIKE '%s' ";
        return $this->getRequestWithFilters($request, $attributes);
    }
    
    /**
     * @param int $id
     * @return MatiereEnseignantClass
     * @since v2.23.01.02
     * @version v2.23.01.02
     */
    public function getMatiereEnseignantById($id)
    {
        //////////////////////////////
        // Construction de la requête
        $request  = "SELECT id, matiereId, enseignantId FROM ".$this->dbTable." ";
        $request .= "WHERE id='%s' ";
        $prepRequest = vsprintf($request, array($id));
        
        //////////////////////////////
        // Exécution de la requête
        $row = MySQLClass::wpdbSelect($prepRequest);
        return (empty($row) ? new MatiereEnseignantClass() : new MatiereEnseignantClass($row[0]));
        //////////////////////////////
    }
    
    /**
     * @param array $attributes
     * @return int
     * @since v2.23.01.02
     * @version v2.23.01.02
     */
    public function insertMatiereEnseignant($attributes)
    {
        //////////////////////////////
        // Construction de la requête
        $request  = "INSERT INTO ".$this->dbTable." (matiereId, enseignantId) ";
        $request .= "VALUES ('%s', '%s');";
        return parent::insert($request, $attributes);
    }
    
    /**
     * @param array $attributes
     * @since v2.23.01.02
     * @version v2.23.01.02
     */
    public function updateMatiereEnseignant($attributes)
    {
        //////////////////////////////
        // Construction de la requête
        $request  = "UPDATE ".$this->dbTable." SET ";
        $request .= "matiereId = '%s', ";
        $request .= "enseignantId = '%s' ";
        $request .= "WHERE id = '%s';";
        parent::update($request, $attributes);
    }
    
    /**
     * @param array $attributes
     * @since v2.23.01.02
     * @version v2.23.01.02
     */
    public function deleteMatiereEnseignant($attributes)
    {
        //////////////////////////////
        // Construction de la requête
        $request  = "DELETE FROM ".$this->dbTable." ";
        $request .= "WHERE id = '%s';";
        parent::delete($request, $attributes);
    }
}
