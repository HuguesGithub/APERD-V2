<?php
namespace core\bean;

use core\domain\EleveClass;

if (!defined('ABSPATH')) {
    die('Forbidden');
}
/**
 * Classe EleveBean
 * @author Hugues
 * @since 2.22.12.22
 * @version 2.22.12.22
 */
class EleveBean extends LocalBean
{
    /**
     * Class Constructor
     * @param EleveClass $objEleve
     * @since 2.22.12.22
     * @version 2.22.12.22
     */
    public function __construct($objEleve='')
    {
        parent::__construct();
        $this->obj = ($objEleve=='' ? new EleveClass() : $objEleve);
    }
    
    //////////////////////////////////////////////////
    // METHODES
    //////////////////////////////////////////////////
    /**
     * @param boolean $blnHasEditorRights
     * @param string $baseUrl
     * @param boolean $blnChecked
     * @return string
     * @since 2.22.12.22
     * @version 2.22.12.22
     */
    public function getRow($blnHasEditorRights, $blnChecked=false)
    {
        $attributes = array(self::ATTR_CLASS=>self::CST_TEXT_WHITE);
        $trContent = '';
        
        ///////////////////////////////////////////////
        // Les cases à cocher
        if ($blnHasEditorRights) {
            $id = $this->obj->getField(self::FIELD_ID);
            
            $this->baseUrl  = '/'.self::PAGE_ADMIN;
            $this->baseUrl .= '?'.self::CST_ONGLET.'='.self::ONGLET_ELEVES;
            $this->baseUrl .= self::CST_AMP.self::FIELD_ID.'='.$id;
                        
            // Case à cocher
            $trContent  = $this->getCellInput($blnChecked);
        }
        ///////////////////////////////////////////////
        
        ///////////////////////////////////////////////
        // La partie commune
        // Nom sans le lien d'édition
        $label = $this->getBalise(self::TAG_STRONG, $this->obj->getName());
        $trContent .= $this->getBalise(self::TAG_TD, $label, $attributes);
        
        // Division
        $label = $this->getBalise(self::TAG_STRONG, $this->obj->getDivision()->getField(self::FIELD_LABELDIVISION));
        $trContent .= $this->getBalise(self::TAG_TD, $label, $attributes);
        
        // Délégué
        $blnChecked = ($this->obj->getField(self::FIELD_DELEGUE)==1);
        $trContent .= $this->getIconCheckbox($blnChecked);
        ///////////////////////////////////////////////
        
        ///////////////////////////////////////////////
        // Les boutons d'action
        if ($blnHasEditorRights) {
            // Actions
            $trContent .= $this->getCellActions();
        }
        return $this->getBalise(self::TAG_TR, $trContent, $attributes);
    }
    
    /**
     * @param string $baseUrl
     * @return string
     * @since 2.22.12.22
     * @version 2.22.12.22
     */
    public function getForm($baseUrl, $strNotifications='')
    {
        ///////////////////////////////////////////////
        // Construction de la liste des divisions
        $strOptions = $this->getBalise(self::TAG_OPTION);
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
            $strOptions .= $this->getBalise(self::TAG_OPTION, $divLabel, $attributes);
        }
        ///////////////////////////////////////////////
        
        $urlTemplate = self::WEB_A_FORM_ELEVE;
        $attributes = array(
            // Création - 1
            ($this->obj->getField(self::FIELD_ID)=='' ? self::LABEL_CREER : self::LABEL_MODIFIER),
            // Identifiant de l'Administration - 2
            $this->obj->getField(self::FIELD_ID),
            // Annuler - 3
            $baseUrl,
            // Message de confirmation ou d'erreur - 4
            $strNotifications,
            // Nom de l'Elève- 5
            $this->obj->getField(self::FIELD_NOMELEVE),
            // Prénom de l'Elève- 5
            $this->obj->getField(self::FIELD_PRENOMELEVE),
            // Division - 6
            $strOptions,
            // Délégué - 7
            ($this->obj->getField(self::FIELD_DELEGUE)==1 ? ' '.self::CST_CHECKED : ''),
        );
        return $this->getRender($urlTemplate, $attributes);
    }
}
