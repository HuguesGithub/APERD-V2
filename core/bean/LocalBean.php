<?php
namespace core\bean;

if (!defined('ABSPATH')) {
    die('Forbidden');
}
/**
 * Classe LocalBean
 * @author Hugues
 * @since 2.22.12.05
 * @version 2.22.12.05
 */
class LocalBean extends UtilitiesBean
{
    public function __construct()
    {
        $this->hasEdit = true;
    }
    
    /**
     * @return string
     * @since 2.22.12.08
     * @version 2.22.12.08
     */
    protected function getCellActions()
    {
        if ($this->hasEdit) {
            // Bouton Edition
            $urlEdition = $this->baseUrl.self::CST_AMP.self::CST_ACTION.'='.self::CST_WRITE;
            $strIcon = $this->getIcon(self::I_EDIT);
            $strButton = $this->getButton($strIcon, array(self::ATTR_CLASS=>'btn btn-sm btn-primary'));
            $divContent  = $this->getLink($strButton, $urlEdition, '', array(self::ATTR_TITLE=>self::LABEL_MODIFIER));
            $divContent .= self::CST_NBSP;
        } else {
            $divContent = '';
        }
        
        // Bouton Suppression
        $urlSuppression = $this->baseUrl.self::CST_AMP.self::CST_ACTION.'='.self::CST_DELETE;
        $strIcon = $this->getIcon(self::I_DELETE);
        $strButton = $this->getButton($strIcon, array(self::ATTR_CLASS=>'btn btn-sm btn-danger'));
        $divContent .= $this->getLink($strButton, $urlSuppression, '', array(self::ATTR_TITLE=>self::LABEL_SUPPRIMER));
        
        $tdContent = $this->getDiv($divContent, array(self::ATTR_CLASS=>'row-actions text-center'));
        return $this->getBalise(self::TAG_TD, $tdContent, array(self::ATTR_CLASS=>'column-actions'));
    }
    
    /**
     * @param boolean $blnChecked
     * @return string
     * @since 2.22.12.05
     * @version 2.22.12.07
     */
    protected function getCellInput($blnChecked)
    {
        $id = $this->obj->getField(self::FIELD_ID);
        $attributes = array(
            self::ATTR_ID   => 'cb-select-'.$id,
            self::ATTR_NAME  => 'post[]',
            self::ATTR_VALUE => $id,
            self::ATTR_TYPE  => 'checkbox',
        );
        if ($blnChecked) {
            $attributes[self::CST_CHECKED] = self::CST_CHECKED;
        }
        return $this->getBalise(self::TAG_TD, $this->getBalise(self::TAG_INPUT, '', $attributes));
    }
    
    /**
     * Retourne une checkbox en fontawesome selon la valeur
     * @param boolean $blnChecked
     * @return string
     * @since v2.22.12.18
     * @version v2.22.12.18
     */
    public function getIconCheckbox($blnChecked)
    {
        if ($blnChecked) {
            $extra = 'btn-success';
            $strIcon = $this->getIcon(self::I_SQUARE_CHECK);
        } else {
            $extra = 'btn-danger';
            $strIcon = $this->getIcon(self::I_SQUARE_XMARK);
        }
        $label = $this->getButton($strIcon, array(self::ATTR_CLASS=>'disabled '.$extra));
        $attributes = array(self::ATTR_CLASS=>self::CST_TEXT_WHITE.' text-center');
        return $this->getBalise(self::TAG_TD, $label, $attributes);
    }
}
