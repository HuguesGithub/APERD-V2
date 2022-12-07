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
    
}
