<?php
namespace core\daoimpl;

use core\domain\AdulteClass;
use core\domain\MySQLClass;

if (!defined('ABSPATH')) {
    die('Forbidden');
}
/**
 * Classe AdulteDaoImpl
 * @author Hugues
 * @since 2.22.12.08
 * @version 2.22.12.08
 */
class AdulteDaoImpl extends LocalDaoImpl
{
    //////////////////////////////////////////////////
    // CONSTRUCT
    //////////////////////////////////////////////////
    
    /**
     * @since 2.22.12.08
     * @version 2.22.12.08
     */
    public function __construct()
    {
        $this->dbTable = self::DB_PREFIX.'adulte';
        $this->select = "SELECT id, nomAdulte, prenomAdulte, mailAdulte, adherent, phoneAdulte, roleAdulte FROM ";
        parent::__construct();
    }
    
    /**
     * @param array $rows
     * @return array
     * @since 2.22.12.08
     * @version 2.22.12.08
     */
    protected function convertToArray($rows)
    {
        $objItems = array();
        while (!empty($rows)) {
            $row = array_shift($rows);
            $objItems[] = new AdulteClass($row);
        }
        return $objItems;
    }
    
    /**
     * @param array $attributes
     * @return array
     * @since 2.22.12.08
     * @version 2.22.12.08
     */
    public function getAdultesWithFilters($attributes)
    {
        //////////////////////////////
        // Construction de la requête
        $request  = $this->select.$this->dbTable;
        $request .= " WHERE 1=1 AND nomAdulte LIKE '%s' AND mailAdulte LIKE '%s' AND adherent LIKE '%s' ";
        return $this->getRequestWithFilters($request, $attributes);
    }
    
    /**
     * @param int $id
     * @return AdulteClass
     * @since 2.22.12.08
     * @version 2.22.12.08
     */
    public function getAdulteById($id)
    {
        //////////////////////////////
        // Construction de la requête
        $request  = $this->select.$this->dbTable;
        $request .= " WHERE id='%s' ";
        $prepRequest = vsprintf($request, array($id));
        
        //////////////////////////////
        // Exécution de la requête
        $row = MySQLClass::wpdbSelect($prepRequest);
        return (empty($row) ? new AdulteClass() : new AdulteClass($row[0]));
        //////////////////////////////
    }
    
    /**
     * On recherche un Adulte dont le nom + prenom correspond au paramètre
     * @param string $nomPrenomAdulte
     * @return \core\domain\AdulteClass
     * @since v2.22.12.19
     * @version v2.22.12.19
     */
    public function getAdulteByNomPrenom($nomPrenomAdulte)
    {
        //////////////////////////////
        // Construction de la requête
        $request  = $this->select.$this->dbTable;
        $request .= " WHERE CONCAT_WS(' ', nomAdulte, prenomAdulte)='%s' ";
        $prepRequest = vsprintf($request, array($nomPrenomAdulte));
        
        //////////////////////////////
        // Exécution de la requête
        $row = MySQLClass::wpdbSelect($prepRequest);
        return (empty($row) ? new AdulteClass() : new AdulteClass($row[0]));
        //////////////////////////////
    }
    
    /**
     * @param array $attributes
     * @return int
     * @since 2.22.12.08
     * @version 2.22.12.08
     */
    public function insertAdulte($attributes)
    {
        //////////////////////////////
        // Construction de la requête
        $request  = "INSERT INTO ".$this->dbTable." (nomAdulte, prenomAdulte, mailAdulte, adherent,";
        $request .= " phoneAdulte, roleAdulte) ";
        $request .= "VALUES ('%s', '%s', '%s', '%s', '%s', '%s');";
        return parent::insert($request, $attributes);
    }
    
    /**
     * @param array $attributes
     * @since 2.22.12.08
     * @version 2.22.12.08
     */
    public function updateAdulte($attributes)
    {
        //////////////////////////////
        // Construction de la requête
        $request  = "UPDATE ".$this->dbTable." SET ";
        $request .= "nomAdulte = '%s', ";
        $request .= "prenomAdulte = '%s', ";
        $request .= "mailAdulte = '%s', ";
        $request .= "adherent = '%s', ";
        $request .= "phoneAdulte = '%s', ";
        $request .= "roleAdulte = '%s' ";
        $request .= "WHERE id = '%s';";
        parent::update($request, $attributes);
    }
    
    /**
     * @param array $attributes
     * @since 2.22.12.08
     * @version 2.22.12.08
     */
    public function deleteAdulte($attributes)
    {
        //////////////////////////////
        // Construction de la requête
        $request  = "DELETE FROM ".$this->dbTable." ";
        $request .= "WHERE id = '%s';";
        parent::delete($request, $attributes);
    }
}
