<?php
namespace core\daoimpl;

use core\interfaceimpl\ConstantsInterface;
use core\interfaceimpl\UrlsInterface;
use core\interfaceimpl\LabelsInterface;
use core\domain\MySQLClass;

if (!defined('ABSPATH')) {
    die('Forbidden');
}
/**
 * Classe LocalDaoImpl
 * @author Hugues
 * @since 2.22.12.05
 * @version 2.22.12.07
 */
class LocalDaoImpl implements ConstantsInterface, UrlsInterface, LabelsInterface
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
    }
    
    /**
     * @param string $request
     * @param string $attributes
     * @return array
     * @since 2.22.12.06
     * @version 2.22.12.06
     */
    public function getRequestWithFilters($request, $attributes)
    {
        
        $request .= "ORDER BY ";
        if (is_array($attributes[self::SQL_ORDERBY])) {
            while (!empty($attributes[self::SQL_ORDERBY])) {
                $orderBy = array_shift($attributes[self::SQL_ORDERBY]);
                $order = array_shift($attributes[self::SQL_ORDER]);
                $request .= $orderBy." $order, ";
            }
            $request = substr($request, 0, -2).";";
        } elseif ($attributes[self::SQL_ORDERBY]==self::SQL_ORDER_RAND) {
            $request .= self::SQL_ORDER_RAND." ";
        } else {
            $request .= $attributes[self::SQL_ORDERBY]." ".$attributes[self::SQL_ORDER]." ";
        }
        
        if (isset($attributes[self::SQL_LIMIT]) && $attributes[self::SQL_LIMIT]!=-1) {
            $request .= "LIMIT ".$attributes[self::SQL_LIMIT];
        }
        
        $prepRequest = vsprintf($request, $attributes[self::SQL_WHERE]);
        
        //////////////////////////////
        // Exécution de la requête
        $rows = MySQLClass::wpdbSelect($prepRequest);
        return $this->convertToArray($rows);
        //////////////////////////////
    }
    
    /**
     * @param string $request
     * @param array $attributes
     * @return int
     * @since 2.22.12.07
     * @version 2.22.12.07
     */
    public function insert($request, $attributes)
    {
        $this->crud($request, $attributes);
        return MySQLClass::getLastInsertId();
    }
    
    /**
     * @param string $request
     * @param array $attributes
     * @since 2.22.12.07
     * @version 2.22.12.07
     */
    public function update($request, $attributes)
    { $this->crud($request, $attributes); }
    
    /**
     * @param string $request
     * @param array $attributes
     * @since 2.22.12.07
     * @version 2.22.12.07
     */
    public function delete($request, $attributes)
    { $this->crud($request, $attributes); }
    
    /**
     * @param string $request
     * @param array $attributes
     * @since 2.22.12.07
     * @version 2.22.12.07
     */
    public function crud($request, $attributes)
    {
        $prepRequest = vsprintf($request, $attributes);
        //////////////////////////////
        // Exécution de la requête
        MySQLClass::wpdbQuery($prepRequest);
        //////////////////////////////
    }
}