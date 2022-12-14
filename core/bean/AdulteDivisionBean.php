<?php
namespace core\bean;

use core\domain\AdulteDivisionClass;

if (!defined('ABSPATH')) {
    die('Forbidden');
}
/**
 * Classe AdulteBean
 * @author Hugues
 * @since 2.22.12.12
 * @version 2.22.12.14
 */
class AdulteDivisionBean extends LocalBean
{
    /**
     * Class Constructor
     * @param AdulteDivisionClass $objAdulteDivision
     * @since 2.22.12.12
     * @version 2.22.12.14
     */
    public function __construct($objAdulteDivision='')
    {
        parent::__construct();
        $this->obj = ($objAdulteDivision=='' ? new AdulteDivisionClass() : $objAdulteDivision);
    }
    
    //////////////////////////////////////////////////
    // METHODES
    //////////////////////////////////////////////////
    /**
     * @param boolean $blnHasEditorRights
     * @param string $baseUrl
     * @param boolean $blnChecked
     * @return string
     * @since 2.22.12.12
     * @version 2.22.12.12
     */
    public function getRow($blnHasEditorRights, $blnChecked=false)
    {
        $attributes = array(self::ATTR_CLASS=>self::CST_TEXT_WHITE);
        
        ///////////////////////////////////////////////
        // Les cases à cocher
        if ($blnHasEditorRights) {
            $id = $this->obj->getField(self::FIELD_ID);
            
            $this->baseUrl  = '/'.self::PAGE_ADMIN;
            $this->baseUrl .= '?'.self::CST_ONGLET.'='.self::ONGLET_PARENTS;
            $this->baseUrl .= self::CST_AMP.self::FIELD_ID.'='.$id;
            
            // Case à cocher
            $trContent  = $this->getCellInput($blnChecked);
        }
        ///////////////////////////////////////////////
        
        ///////////////////////////////////////////////
        // La partie commune
        // Nom sans le lien d'édition
        $label = $this->getBalise(self::TAG_STRONG, $this->obj->getAdulte()->getName());
        $trContent .= $this->getBalise(self::TAG_TD, $label, $attributes);
        
        // Division
        $label = $this->getBalise(self::TAG_STRONG, $this->obj->getDivision()->getField(self::FIELD_LABELDIVISION));
        $trContent .= $this->getBalise(self::TAG_TD, $label, $attributes);

        // Mail
        if ($this->obj->getAdulte()->getField(self::FIELD_MAILADULTE)!='') {
            $extra = 'btn-success';
            $strIcon = $this->getIcon(self::I_SQUARE_CHECK);
        } else {
            $extra = 'btn-danger';
            $strIcon = $this->getIcon(self::I_SQUARE_XMARK);
        }
        $strMail = $this->getButton($strIcon, array(self::ATTR_CLASS=>'disabled '.$extra));
        $mailAttributes = array(self::ATTR_CLASS=>self::CST_TEXT_WHITE.' text-center');
        $trContent .= $this->getBalise(self::TAG_TD, $strMail, $mailAttributes);
        
        // Adhérent
        if ($this->obj->getAdulte()->getField(self::FIELD_ADHERENT)==1) {
            $extra = 'btn-success';
            $strIcon = $this->getIcon(self::I_SQUARE_CHECK);
        } else {
            $extra = 'btn-danger';
            $strIcon = $this->getIcon(self::I_SQUARE_XMARK);
        }
        $strAdherent = $this->getButton($strIcon, array(self::ATTR_CLASS=>'disabled '.$extra));
        $adhAttributes = array(self::ATTR_CLASS=>self::CST_TEXT_WHITE.' text-center');
        $trContent .= $this->getBalise(self::TAG_TD, $strAdherent, $adhAttributes);
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
}
