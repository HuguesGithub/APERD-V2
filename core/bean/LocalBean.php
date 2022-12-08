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

    /**
     * @param boolean $blnChecked
     * @return string
     * @since 2.22.12.05
     * @version 2.22.12.07
     */
    public function getCellInput($blnChecked)
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
     * @return string
     * @since 2.22.12.08
     * @version 2.22.12.08
     */
    public function getCellActions()
    {
        // Bouton Edition
        $urlEdition = $this->baseUrl.self::CST_AMP.self::CST_SUBONGLET.'='.self::CST_WRITE;
        $strIcon = $this->getIcon(self::I_EDIT);
        $strButton = $this->getButton($strIcon, array(self::ATTR_CLASS=>'btn btn-sm btn-primary'));
        $divContent  = $this->getLink($strButton, $urlEdition, '', array(self::ATTR_TITLE=>self::LABEL_MODIFIER));
        
        $divContent .= self::CST_NBSP;
        
        // Bouton Suppression
        $urlSuppression = $this->baseUrl.self::CST_AMP.self::CST_SUBONGLET.'='.self::CST_DELETE;
        $strIcon = $this->getIcon(self::I_DELETE);
        $strButton = $this->getButton($strIcon, array(self::ATTR_CLASS=>'btn btn-sm btn-danger'));
        $divContent .= $this->getLink($strButton, $urlSuppression, '', array(self::ATTR_TITLE=>self::LABEL_SUPPRIMER));
        
        $tdContent = $this->getDiv($divContent, array(self::ATTR_CLASS=>'row-actions'));
        return $this->getBalise(self::TAG_TD, $tdContent, array(self::ATTR_CLASS=>'column-actions'));
    }
}
