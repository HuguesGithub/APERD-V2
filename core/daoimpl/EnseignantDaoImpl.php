<?php
namespace core\daoimpl;

use core\domain\EnseignantClass;
use core\domain\MySQLClass;

if (!defined('ABSPATH')) {
    die('Forbidden');
}
/**
 * Classe EnseignantDaoImpl
 * @author Hugues
 * @since v2.23.01.02
 * @version v2.23.01.02
 */
class EnseignantDaoImpl extends LocalDaoImpl
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
        $this->dbTable = self::DB_PREFIX.'enseignant';
        $this->select = "SELECT id, genre, nomEnseignant, prenomEnseignant FROM ";
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
            $objItems[] = new EnseignantClass($row);
        }
        return $objItems;
    }
    
    /**
     * @param array $attributes
     * @return array
     * @since v2.23.01.02
     * @version v2.23.01.02
     */
    public function getEnseignantsWithFilters($attributes)
    {
        //////////////////////////////
        // Construction de la requête
        $request  = $this->select.$this->dbTable;
        $request .= " WHERE 1=1 AND nomEnseignant LIKE '%s' ";
        return $this->getRequestWithFilters($request, $attributes);
    }
    
    /**
     * @param int $id
     * @return EnseignantClass
     * @since v2.23.01.02
     * @version v2.23.01.02
     */
    public function getEnseignantById($id)
    {
        //////////////////////////////
        // Construction de la requête
        $request  = $this->select.$this->dbTable;
        $request .= " WHERE id='%s' ";
        $prepRequest = vsprintf($request, array($id));
        
        //////////////////////////////
        // Exécution de la requête
        $row = MySQLClass::wpdbSelect($prepRequest);
        return (empty($row) ? new EnseignantClass() : new EnseignantClass($row[0]));
        //////////////////////////////
    }
    
    /**
     * On recherche un Enseignant dont le nom + prenom correspond au paramètre
     * @param string $nomPrenomEnseignant
     * @return \core\domain\EnseignantClass
     * @since v2.23.01.02
     * @version v2.23.01.02
     */
    public function getEnseignantByNomPrenom($nomPrenomEnseignant)
    {
        //////////////////////////////
        // Construction de la requête
        $request  = $this->select.$this->dbTable;
        $request .= " WHERE CONCAT_WS(' ', nomEnseignant, prenomEnseignant)='%s' ";
        $prepRequest = vsprintf($request, array($nomPrenomEnseignant));
        
        //////////////////////////////
        // Exécution de la requête
        $row = MySQLClass::wpdbSelect($prepRequest);
        return (empty($row) ? new EnseignantClass() : new EnseignantClass($row[0]));
        //////////////////////////////
    }
    
    /**
     * @param array $attributes
     * @return int
     * @since v2.23.01.02
     * @version v2.23.01.02
     */
    public function insertEnseignant($attributes)
    {
        //////////////////////////////
        // Construction de la requête
        $request  = "INSERT INTO ".$this->dbTable." (genre, nomEnseignant, prenomEnseignant) ";
        $request .= "VALUES ('%s', '%s', '%s');";
        return parent::insert($request, $attributes);
    }
    
    /**
     * @param array $attributes
     * @since v2.23.01.02
     * @version v2.23.01.02
     */
    public function updateEnseignant($attributes)
    {
        //////////////////////////////
        // Construction de la requête
        $request  = "UPDATE ".$this->dbTable." SET ";
        $request .= "genre = '%s', ";
        $request .= "nomEnseignant = '%s', ";
        $request .= "prenomEnseignant = '%s' ";
        $request .= "WHERE id = '%s';";
        parent::update($request, $attributes);
    }
    
    /**
     * @param array $attributes
     * @since v2.23.01.02
     * @version v2.23.01.02
     */
    public function deleteEnseignant($attributes)
    {
        //////////////////////////////
        // Construction de la requête
        $request  = "DELETE FROM ".$this->dbTable." ";
        $request .= "WHERE id = '%s';";
        parent::delete($request, $attributes);
    }
}
