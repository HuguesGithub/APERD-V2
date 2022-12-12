<?php
namespace core\bean;

use core\services\AdministrationServices;
use core\services\AdulteServices;
use core\services\AdulteDivisionServices;
use core\services\DivisionServices;
use core\interfaceimpl\ConstantsInterface;
use core\interfaceimpl\UrlsInterface;
use core\interfaceimpl\LabelsInterface;

if (!defined('ABSPATH')) {
    die('Forbidden');
}
/**
 * Classe UtilitiesBean
 * @author Hugues
 * @since 2.22.12.05
 * @version 2.22.12.08
 */
class UtilitiesBean implements ConstantsInterface, UrlsInterface, LabelsInterface
{
    public function __construct()
    {
        $this->objAdministrationServices = new AdministrationServices();
        $this->objAdulteServices         = new AdulteServices();
        $this->objAdulteDivisionServices = new AdulteDivisionServices();
        $this->objDivisionServices       = new DivisionServices();
    }
  
    /**
     * @return bool
     */
    public static function isAperdLogged()
    {
        // On va checker dans les variables de SESSION si les infos relatives à l'APERD y sont stockées.
        return (isset($_SESSION[self::SESSION_APERD_ID]) && $_SESSION[self::SESSION_APERD_ID]!=self::CST_ERR_LOGIN);
    }
    
    /**
     * @param string $balise
     * @param string $label
     * @param array $attributes
     * @return string
     * @since 2.22.12.05
     * @version 2.22.12.05
     */
    protected function getBalise($balise, $label='', $attributes=array())
    {
        $strBalise = '<'.$balise.$this->getExtraAttributesString($attributes);
        if (in_array($balise, array(self::TAG_INPUT))) {
            $strBalise .= '/>';
        } else {
            $strBalise .= '>'.$label.'</'.$balise.'>';
        }
        return $strBalise;
    }
    
    /**
     * @param array $attributes
     * @return string
     * @since 2.22.12.05
     * @version 2.22.12.05
     */
    private function getExtraAttributesString($attributes)
    {
        $extraAttributes = '';
        if (!empty($attributes)) {
            foreach ($attributes as $key => $value) {
                $extraAttributes .= ' '.$key.'="'.$value.'"';
            }
        }
        return $extraAttributes;
    }

    /**
     * @param string $urlTemplate
     * @param array $args
     * @return string
     * @since 2.22.12.05
     * @version 2.22.12.05
     */
    public function getRender($urlTemplate, $args=array())
    { return vsprintf(file_get_contents(PLUGIN_PATH.$urlTemplate), $args); }
    
    /**
     * @param string $label
     * @param array $attributes
     * @return string
     * @since 2.22.12.05
     * @version 2.22.12.05
     */
    public function getDiv($label, $attributes=array())
    {
        $divAttributes = array();
        if (!empty($attributes)) {
            foreach ($attributes as $key => $value) {
                $divAttributes[$key]  = $value;
            }
        }
        return $this->getBalise(self::TAG_DIV, $label, $divAttributes);
    }
    
    /**
     * @param string
     * @param string
     * @param string
     * @return string
     * @since 2.22.12.05
     * @version 2.22.12.05
     */
    public function getIcon($tag, $prefix='', $label='')
    {
        if ($prefix!='') {
            $prefix .= ' ';
        }
        $prefix .= 'fa-solid fa-';
        return $this->getBalise(self::TAG_I, $label, array(self::ATTR_CLASS=>$prefix.$tag));
    }
    
    /**
     * @param string $label
     * @param string $href
     * @param string $classe
     * @param array $extraAttributes
     * @return string
     * @since 2.22.12.05
     * @version 2.22.12.05
     */
    public function getLink($label, $href, $classe, $extraAttributes=array())
    {
        $attributes = array(
            self::ATTR_HREF => $href,
            self::ATTR_CLASS => $classe,
        );
        if (!empty($extraAttributes)) {
            foreach ($extraAttributes as $key => $value) {
                $attributes[$key]  = $value;
            }
        }
        return $this->getBalise(self::TAG_A, $label, $attributes);
    }
    
    /**
     * @param string $label
     * @param array $attributes
     * @return string
     * @since 2.22.12.05
     * @version 2.22.12.05
     */
    public function getButton($label, $attributes=array())
    {
        $buttonAttributes = array(
            self::ATTR_TYPE => self::TAG_BUTTON,
            self::ATTR_CLASS => 'btn btn-default btn-sm',
        );
        if (!empty($attributes)) {
            foreach ($attributes as $key => $value) {
                if (!isset($buttonAttributes[$key])) {
                    $buttonAttributes[$key]  = $value;
                } elseif ($key==self::ATTR_CLASS) {
                    $buttonAttributes[$key] .= ' '.$value;
                }
            }
        }
        return $this->getBalise(self::TAG_BUTTON, $label, $buttonAttributes);
    }
    
    /**
     * @param string $label
     * @param array $attributes
     * @return string
     * @since 2.22.12.05
     * @version 2.22.12.05
     */
    public function getTh($label, $attributes=array())
    {
        $buttonAttributes = array(
            'scope' => 'col',
        );
        if (!empty($attributes)) {
            foreach ($attributes as $key => $value) {
                $buttonAttributes[$key]  = $value;
            }
        }
        return $this->getBalise(self::TAG_TH, $label, $buttonAttributes);
    }
    
    /**
     * @return bool
     * @since 2.22.12.05
     * @version 2.22.12.05
     */
    public static function isAdmin()
    { return current_user_can('manage_options'); }
     
    /**
     * @return bool
     * @since 2.22.12.05
     * @version 2.22.12.05
     */
    public static function isLogged()
    { return is_user_logged_in(); }
    
    /**
     * @return int
     * @since 2.22.12.05
     * @version 2.22.12.05
     */
    public static function getWpUserId()
    { return get_current_user_id(); }
      
    /**
     * @param string $id
     * @param string $default
     * @return mixed
     * @since 2.22.12.05
     * @version 2.22.12.05
     */
    public function initVar($id, $default='')
    {
        if (isset($_POST[$id])) {
            return $_POST[$id];
        }
        if (isset($_GET[$id])) {
            return $_GET[$id];
        }
        return $default;
    }
}