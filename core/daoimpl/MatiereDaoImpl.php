<?php
namespace core\daoimpl;

use core\domain\MatiereClass;
use core\domain\MySQLClass;

if (!defined('ABSPATH')) {
    die('Forbidden');
}
/**
 * Classe MatiereDaoImpl
 * @author Hugues
 * @since 2.22.12.21
 * @version 2.22.12.21
 */
class MatiereDaoImpl extends LocalDaoImpl
{
    //////////////////////////////////////////////////
    // CONSTRUCT
    //////////////////////////////////////////////////
    
    /**
     * @since 2.22.12.21
     * @version 2.22.12.21
     */
    public function __construct()
    {
        $this->dbTable = self::DB_PREFIX.'matiere';
        parent::__construct();
    }
    
    /**
     * @param array $rows
     * @return array
     * @since 2.22.12.21
     * @version 2.22.12.21
     */
    protected function convertToArray($rows)
    {
        $objItems = array();
        while (!empty($rows)) {
            $row = array_shift($rows);
            $objItems[] = new MatiereClass($row);
        }
        return $objItems;
    }
    
    /**
     * @param array $attributes
     * @return array
     * @since 2.22.12.21
     * @version 2.22.12.21
     */
    public function getMatieresWithFilters($attributes)
    {
        //////////////////////////////
        // Construction de la requête
        $request  = "SELECT id, labelMatiere FROM ".$this->dbTable." ";
        $request .= "WHERE 1=1 AND labelMatiere LIKE '%s' ";
        return $this->getRequestWithFilters($request, $attributes);
    }
    
    /**
     * @param int $id
     * @return MatiereClass
     * @since 2.22.12.21
     * @version 2.22.12.21
     */
    public function getMatiereById($id)
    {
        //////////////////////////////
        // Construction de la requête
        $request  = "SELECT id, labelMatiere FROM ".$this->dbTable." ";
        $request .= "WHERE id='%s' ";
        $prepRequest = MySQLClass::wpdbPrepare($request, array($id));
        
        //////////////////////////////
        // Exécution de la requête
        $row = MySQLClass::wpdbSelect($prepRequest);
        return (empty($row) ? new MatiereClass() : new MatiereClass($row[0]));
        //////////////////////////////
    }
    
    /**
     * @param array $attributes
     * @return int
     * @since 2.22.12.21
     * @version 2.22.12.21
     */
    public function insertMatiere($attributes)
    {
        //////////////////////////////
        // Construction de la requête
        $request  = "INSERT INTO ".$this->dbTable." (labelMatiere) ";
        $request .= "VALUES ('%s');";
        return parent::insert($request, $attributes);
    }
    
    /**
     * @param array $attributes
     * @since 2.22.12.21
     * @version 2.22.12.21
     */
    public function updateMatiere($attributes)
    {
        //////////////////////////////
        // Construction de la requête
        $request  = "UPDATE ".$this->dbTable." SET ";
        $request .= "libelleMatiere = '%s' ";
        $request .= "WHERE id = '%s';";
        parent::update($request, $attributes);
    }
    
    /**
     * @param array $attributes
     * @since 2.22.12.21
     * @version 2.22.12.21
     */
    public function deleteMatiere($attributes)
    {
        //////////////////////////////
        // Construction de la requête
        $request  = "DELETE FROM ".$this->dbTable." ";
        $request .= "WHERE id = '%s';";
        parent::delete($request, $attributes);
    }
}
