<?php
namespace core\daoimpl;

use core\domain\DivisionClass;
use core\domain\MySQLClass;

if (!defined('ABSPATH')) {
    die('Forbidden');
}
/**
 * Classe DivisionDaoImpl
 * @author Hugues
 * @since 2.22.12.11
 * @version 2.22.12.11
 */
class DivisionDaoImpl extends LocalDaoImpl
{
    //////////////////////////////////////////////////
    // CONSTRUCT
    //////////////////////////////////////////////////
    
    /**
     * @since 2.22.12.11
     * @version 2.22.12.11
     */
    public function __construct()
    {
        $this->dbTable = self::__PREFIX_DB__.'division';
        parent::__construct();
    }
    
    /**
     * @param array $rows
     * @return array
     * @since 2.22.12.11
     * @version 2.22.12.11
     */
    protected function convertToArray($rows)
    {
        $objItems = array();
        while (!empty($rows)) {
            $row = array_shift($rows);
            $objItems[] = new DivisionClass($row);
        }
        return $objItems;
    }
    
    /**
     * @param array $attributes
     * @return array
     * @since 2.22.12.11
     * @version 2.22.12.11
     */
    public function getDivisionsWithFilters($attributes)
    {
        //////////////////////////////
        // Construction de la requête
        $request  = "SELECT id, labelDivision FROM ".$this->dbTable." ";
        $request .= "WHERE 1=1 AND labelDivision LIKE '%s' ";
        return $this->getRequestWithFilters($request, $attributes);
    }
    
    /**
     * @param int $id
     * @return DivisionClass
     * @since 2.22.12.11
     * @version 2.22.12.11
     */
    public function getDivisionById($id)
    {
        //////////////////////////////
        // Construction de la requête
        $request  = "SELECT id, labelDivision FROM ".$this->dbTable." ";
        $request .= "WHERE id='%s' ";
        $prepRequest = vsprintf($request, array($id));
        
        //////////////////////////////
        // Exécution de la requête
        $row = MySQLClass::wpdbSelect($prepRequest);
        return (empty($row) ? new DivisionClass() : new DivisionClass($row[0]));
        //////////////////////////////
    }
    
    /**
     * @param array $attributes
     * @return int
     * @since 2.22.12.11
     * @version 2.22.12.11
     */
    public function insertDivision($attributes)
    {
        //////////////////////////////
        // Construction de la requête
        $request  = "INSERT INTO ".$this->dbTable." (labelDivision) ";
        $request .= "VALUES ('%s');";
        return parent::insert($request, $attributes);
    }
    
    /**
     * @param array $attributes
     * @since 2.22.12.11
     * @version 2.22.12.11
     */
    public function updateDivision($attributes)
    {
        //////////////////////////////
        // Construction de la requête
        $request  = "UPDATE ".$this->dbTable." SET ";
        $request .= "libelleDivision = '%s' ";
        $request .= "WHERE id = '%s';";
        parent::update($request, $attributes);
    }
    
    /**
     * @param array $attributes
     * @since 2.22.12.11
     * @version 2.22.12.11
     */
    public function deleteDivision($attributes)
    {
        //////////////////////////////
        // Construction de la requête
        $request  = "DELETE FROM ".$this->dbTable." ";
        $request .= "WHERE id = '%s';";
        parent::delete($request, $attributes);
    }
}
