<?php
namespace core\daoimpl;

use core\domain\AdulteDivisionClass;
use core\domain\MySQLClass;

if (!defined('ABSPATH')) {
    die('Forbidden');
}
/**
 * Classe AdulteDivisionDaoImpl
 * @author Hugues
 * @since 2.22.12.12
 * @version 2.22.12.12
 */
class AdulteDivisionDaoImpl extends LocalDaoImpl
{
    //////////////////////////////////////////////////
    // CONSTRUCT
    //////////////////////////////////////////////////
    
    /**
     * @since 2.22.12.12
     * @version 2.22.12.12
     */
    public function __construct()
    {
        $this->dbTable = self::DB_PREFIX.'adulte_division';
        $this->dbTable_adulte = self::DB_PREFIX.'adulte';
        $this->dbTable_division = self::DB_PREFIX.'division';
        parent::__construct();
    }
    
    /**
     * @param array $rows
     * @return array
     * @since 2.22.12.12
     * @version 2.22.12.12
     */
    protected function convertToArray($rows)
    {
        $objItems = array();
        while (!empty($rows)) {
            $row = array_shift($rows);
            $objItems[] = new AdulteDivisionClass($row);
        }
        return $objItems;
    }
    
    /**
     * @param array $attributes
     * @return array
     * @since 2.22.12.12
     * @version 2.22.12.14
     */
    public function getAdulteDivisionsWithFilters($attributes)
    {
        //////////////////////////////
        // Construction de la requête
        $request  = "SELECT ad.id AS id, adulteId, divisionId, delegue FROM ".$this->dbTable." AS ad ";
        $request .= "INNER JOIN ".$this->dbTable_adulte." AS a ON a.id=adulteId ";
        $request .= "INNER JOIN ".$this->dbTable_division." AS d ON d.id=divisionId ";
        $request .= "WHERE 1=1 AND adulteId LIKE '%s' AND divisionId LIKE '%s' AND delegue LIKE '%s' ";
        $request .= "AND adherent LIKE '%s' ";
        return $this->getRequestWithFilters($request, $attributes);
    }
    
    /**
     * @param int $id
     * @return AdulteDivisionClass
     * @since 2.22.12.12
     * @version 2.22.12.12
     */
    public function getAdulteDivisionById($id)
    {
        //////////////////////////////
        // Construction de la requête
        $request  = "SELECT id, adulteId, divisionId, delegue FROM ".$this->dbTable." ";
        $request .= "WHERE id='%s' ";
        $prepRequest = vsprintf($request, array($id));
        
        //////////////////////////////
        // Exécution de la requête
        $row = MySQLClass::wpdbSelect($prepRequest);
        return (empty($row) ? new AdulteDivisionClass() : new AdulteDivisionClass($row[0]));
        //////////////////////////////
    }
    
    /**
     * @param array $attributes
     * @return int
     * @since 2.22.12.12
     * @version 2.22.12.12
     */
    public function insertAdulteDivision($attributes)
    {
        //////////////////////////////
        // Construction de la requête
        $request  = "INSERT INTO ".$this->dbTable." (adulteId, divisionId, delegue) ";
        $request .= "VALUES ('%s', '%s', '%s');";
        return parent::insert($request, $attributes);
    }
    
    /**
     * @param array $attributes
     * @since 2.22.12.12
     * @version 2.22.12.12
     */
    public function updateAdulteDivision($attributes)
    {
        //////////////////////////////
        // Construction de la requête
        $request  = "UPDATE ".$this->dbTable." SET ";
        $request .= "adulteId = '%s', ";
        $request .= "divisionId = '%s', ";
        $request .= "delegue = '%s' ";
        $request .= "WHERE id = '%s';";
        parent::update($request, $attributes);
    }
    
    /**
     * @param array $attributes
     * @since 2.22.12.12
     * @version 2.22.12.12
     */
    public function deleteAdulteDivision($attributes)
    {
        //////////////////////////////
        // Construction de la requête
        $request  = "DELETE FROM ".$this->dbTable." ";
        $request .= "WHERE id = '%s';";
        parent::delete($request, $attributes);
    }
}
