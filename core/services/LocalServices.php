<?php
namespace core\services;

use core\interfaceimpl\ConstantsInterface;
use core\interfaceimpl\UrlsInterface;
use core\interfaceimpl\LabelsInterface;

if (!defined('ABSPATH')) {
    die('Forbidden');
}
/**
 * Classe LocalServices
 * @author Hugues
 * @since 2.22.12.06
 * @version 2.22.12.07
 */
class LocalServices implements ConstantsInterface, UrlsInterface, LabelsInterface
{
    //////////////////////////////////////////////////
    // ATTRIBUTES
    //////////////////////////////////////////////////
    protected $objDao;

    /**
     * @param array $arrFilters
     * @param string $tag
     * @return boolean
     * @since 2.22.12.07
     * @version 2.22.12.07
     */
    protected function isNonEmptyAndNoArray($arrFilters, $tag)
    { return !empty($arrFilters[$tag]) && !is_array($arrFilters[$tag]); }
    
    /**
     * @param array $arrFilters
     * @param string $tag
     * @param string $default
     * @return string
     * @since 2.22.12.07
     * @version 2.22.12.07
     */
    protected function getValueToSearch($arrFilters, $tag, $default=self::SQL_JOKER)
    { return ($this->isNonEmptyAndNoArray($arrFilters, $tag) ? $arrFilters[$tag] : $default); }
    
    /**
     * @param mixed $obj
     * @return array
     * @since 2.22.12.08
     * @version 2.22.12.08
     */
    public function getParamsForInsert($obj)
    {
        $arrParams = array();
        foreach (array_keys($obj->getFields()) as $field) {
            if ($field==self::FIELD_ID) {
                continue;
            }
            array_push($arrParams, $obj->getField($field));
        }
        return $arrParams;
    }
    
    /**
     * @param mixed $obj
     * @return array
     * @since 2.22.12.08
     * @version 2.22.12.08
     */
    public function getParamsForUpdate($obj)
    {
        $arrParams = array();
        foreach (array_keys($obj->getFields()) as $field) {
            if ($field==self::FIELD_ID) {
                $id = $obj->getField($field);
                continue;
            }
            array_push($arrParams, $obj->getField($field));
        }
        array_push($arrParams, $id);
        return $arrParams;
    }

    /**
     * @param array $arrFilters
     * @param string $orderBy
     * @param string $order
     * @return array
     * @since 2.22.12.08
     * @version 2.22.12.08
     */
    public function initRequestParams($arrFilters, $orderBy, $order)
    {
        if ($orderBy==self::SQL_ORDER_RAND) {
            $order = '';
        }
        return array(
            self::SQL_ORDERBY => $orderBy,
            self::SQL_ORDER => $order,
            self::SQL_LIMIT => -1,
            self::SQL_WHERE => $this->buildFilters($arrFilters),
        );
    }
}
