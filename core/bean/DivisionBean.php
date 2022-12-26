<?php
namespace core\bean;

use core\domain\DivisionClass;

if (!defined('ABSPATH')) {
    die('Forbidden');
}
/**
 * Classe DivisionBean
 * @author Hugues
 * @since 2.22.12.10
 * @version 2.22.12.14
 */
class DivisionBean extends LocalBean
{
    /**
     * Class Constructor
     * @param DivisionClass $objDivision
     * @since 2.22.12.10
     * @version 2.22.12.14
     */
    public function __construct($objDivision='')
    {
        parent::__construct();
        $this->obj = ($objDivision=='' ? new DivisionClass() : $objDivision);
        $this->hasEdit = false;
    }
    
    //////////////////////////////////////////////////
    // METHODES
    //////////////////////////////////////////////////
    /**
     * @param boolean $blnHasEditorRights
     * @param string $baseUrl
     * @param boolean $blnChecked
     * @return string
     * @since 2.22.12.10
     * @version 2.22.12.10
     */
    public function getRow($blnHasEditorRights, $blnChecked=false)
    {
        $attributes = array(self::ATTR_CLASS=>self::CST_TEXT_WHITE);
        $trContent  = '';
        
        ///////////////////////////////////////////////
        // Les cases à cocher
        if ($blnHasEditorRights) {
            $id = $this->obj->getField(self::FIELD_ID);
            
            $this->baseUrl  = '/'.self::PAGE_ADMIN;
            $this->baseUrl .= '?'.self::CST_ONGLET.'='.self::ONGLET_DIVISIONS;
            $this->baseUrl .= self::CST_AMP.self::FIELD_ID.'='.$id;
                        
            // Case à cocher
            $trContent  = $this->getCellInput($blnChecked);
        }
        ///////////////////////////////////////////////
        
        ///////////////////////////////////////////////
        // La partie commune
        // Libellé sans le lien d'édition
        $label = $this->getBalise(self::TAG_STRONG, $this->obj->getField(self::FIELD_LABELDIVISION));
        $trContent .= $this->getBalise(self::TAG_TD, $label, $attributes);
        ///////////////////////////////////////////////
        
        ///////////////////////////////////////////////
        // Les boutons d'action
        if ($blnHasEditorRights) {
            // Actions
            $trContent .= $this->getCellActions();
        }
        ///////////////////////////////////////////////
        return $this->getBalise(self::TAG_TR, $trContent, $attributes);
    }
    
    /**
     * @param string $baseUrl
     * @return string
     * @since 2.22.12.14
     * @version 2.22.12.14
     */
    public function getForm($baseUrl, $strNotifications='')
    {
        $urlTemplate = self::WEB_A_FORM_DIVISION;
        $attributes = array(
            // Création - 1
            ($this->obj->getField(self::FIELD_ID)=='' ? self::LABEL_CREER : self::LABEL_MODIFIER),
            // Identifiant de la Division - 2
            $this->obj->getField(self::FIELD_ID),
            // Annuler - 3
            $baseUrl,
            // Message de confirmation ou d'erreur - 4
            $strNotifications,
            // Libellé de la Division - 5
            $this->obj->getField(self::FIELD_LABELDIVISION),
        );
        return $this->getRender($urlTemplate, $attributes);
    }
}
