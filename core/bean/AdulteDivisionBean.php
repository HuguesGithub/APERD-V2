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
     * @since 2.22.12.12
     * @version 2.22.12.12
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
            $this->baseUrl .= '?'.self::CST_ONGLET.'='.self::ONGLET_PARENTS;
            $this->baseUrl .= self::CST_AMP.self::CST_SUBONGLET.'='.self::SUBONGLET_PARENTS_DELEGUES;
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
        $blnChecked = ($this->obj->getAdulte()->getField(self::FIELD_MAILADULTE)!='');
        $trContent .= $this->getIconCheckbox($blnChecked);
        
        // Adhérent
        $blnChecked = ($this->obj->getAdulte()->getField(self::FIELD_ADHERENT)==1);
        $trContent .= $this->getIconCheckbox($blnChecked);
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
     * @since 2.22.12.28
     * @version 2.22.12.28
     */
    public function getForm($baseUrl, $strNotifications='')
    {
        ///////////////////////////////////////////////
        // Construction de la liste des adultes
        $strAdulteOptions = $this->getBalise(self::TAG_OPTION);
        $objsAdulte = $this->objAdulteServices->getAdultesWithFilters();
        while (!empty($objsAdulte)) {
            $objAdulte = array_shift($objsAdulte);
            $adulteId = $objAdulte->getField(self::FIELD_ID);
            $adulteLabel = $objAdulte->getName();
            // Construction de la liste des options.
            $attributes = array(self::ATTR_VALUE=>$adulteId);
            if ($adulteId==$this->obj->getField(self::FIELD_ADULTEID)) {
                $attributes[self::ATTR_SELECTED] = ' '.self::ATTR_SELECTED;
            }
            $strAdulteOptions .= $this->getBalise(self::TAG_OPTION, $adulteLabel, $attributes);
        }
        ///////////////////////////////////////////////
        
        ///////////////////////////////////////////////
        // Construction de la liste des divisions
        $strDivOptions = $this->getBalise(self::TAG_OPTION);
        $objsDivision = $this->objDivisionServices->getDivisionsWithFilters();
        while (!empty($objsDivision)) {
            $objDivision = array_shift($objsDivision);
            $divId = $objDivision->getField(self::FIELD_ID);
            $divLabel = $objDivision->getField(self::FIELD_LABELDIVISION);
            // Construction de la liste des options.
            $attributes = array(self::ATTR_VALUE=>$divId);
            if ($divId==$this->obj->getField(self::FIELD_DIVISIONID)) {
                $attributes[self::ATTR_SELECTED] = ' '.self::ATTR_SELECTED;
            }
            $strDivOptions .= $this->getBalise(self::TAG_OPTION, $divLabel, $attributes);
        }
        ///////////////////////////////////////////////
        
        $urlTemplate = self::WEB_A_FORM_ADULTE_DIVISION;
        $attributes = array(
            // Création - 1
            ($this->obj->getField(self::FIELD_ID)=='' ? self::LABEL_CREER : self::LABEL_MODIFIER),
            // Identifiant de l'Objet - 2
            $this->obj->getField(self::FIELD_ID),
            // Annuler - 3
            $baseUrl,
            // Message de confirmation ou d'erreur - 4
            $strNotifications,
            // Adulte - 5
            $strAdulteOptions,
            // Division - 6
            $strDivOptions,
        );
        return $this->getRender($urlTemplate, $attributes);
    }
}
