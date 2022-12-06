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
 * @version 2.22.12.06
 */
class LocalServices implements ConstantsInterface, UrlsInterface, LabelsInterface
{
    //////////////////////////////////////////////////
    // ATTRIBUTES
    //////////////////////////////////////////////////
    protected $objDao;

    
}
