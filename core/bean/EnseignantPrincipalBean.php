<?php
namespace core\bean;

use core\domain\EnseignantPrincipalClass;

if (!defined('ABSPATH')) {
    die('Forbidden');
}
/**
 * Classe EnseignantPrincipalBean
 * @author Hugues
 * @since v2.23.01.04
 * @version v2.23.01.04
 */
class EnseignantPrincipalBean extends LocalBean
{
    /**
     * Class Constructor
     * @param EnseignantPrincipalClass $objAdulteDivision
     * @since v2.23.01.04
     * @version v2.23.01.04
     */
    public function __construct($objEnseignantPrincipal='')
    {
        parent::__construct();
        $this->obj = ($objEnseignantPrincipal=='' ? new EnseignantPrincipalClass() : $objEnseignantPrincipal);
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
     * @since v2.23.01.04
     * @version v2.23.01.04
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
            $this->baseUrl .= '?'.self::CST_ONGLET.'='.self::ONGLET_ENSEIGNANTS;
            $this->baseUrl .= self::CST_AMP.self::CST_SUBONGLET.'='.self::CST_ENSEIGNANTS_PRINCIPAUX;
            $this->baseUrl .= self::CST_AMP.self::FIELD_ID.'='.$id;
            
            // Case à cocher
            $trContent  = $this->getCellInput($blnChecked);
        }
        ///////////////////////////////////////////////
        
        ///////////////////////////////////////////////
        // La partie commune
        // Nom sans le lien d'édition
        $label = $this->getBalise(self::TAG_STRONG, $this->obj->getEnseignant()->getName());
        $trContent .= $this->getBalise(self::TAG_TD, $label, $attributes);
        
        // Division
        $label = $this->getBalise(self::TAG_STRONG, $this->obj->getDivision()->getField(self::FIELD_LABELDIVISION));
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
     * @since v2.23.01.04
     * @version v2.23.01.04
     */
    public function getForm($baseUrl, $strNotifications='')
    {
        ///////////////////////////////////////////////
        // Construction de la liste des enseignant
        $strEnseignantOptions = $this->getBalise(self::TAG_OPTION);
        $objsEnseignant = $this->objEnseignantServices->getEnseignantsWithFilters();
        while (!empty($objsEnseignant)) {
            $objEnseignant = array_shift($objsEnseignant);
            $enseignantId = $objEnseignant->getField(self::FIELD_ID);
            $enseignantLabel = $objEnseignant->getName();
            // Construction de la liste des options.
            $attributes = array(self::ATTR_VALUE=>$enseignantId);
            if ($enseignantId==$this->obj->getField(self::FIELD_ENSEIGNANTID)) {
                $attributes[self::ATTR_SELECTED] = ' '.self::ATTR_SELECTED;
            }
            $strEnseignantOptions .= $this->getBalise(self::TAG_OPTION, $enseignantLabel, $attributes);
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
        
        $urlTemplate = self::WEB_A_FORM_ENSEIGNANT_PRINCIPAL;
        $attributes = array(
            // Création - 1
            ($this->obj->getField(self::FIELD_ID)=='' ? self::LABEL_CREER : self::LABEL_MODIFIER),
            // Identifiant de l'Objet - 2
            $this->obj->getField(self::FIELD_ID),
            // Annuler - 3
            $baseUrl,
            // Message de confirmation ou d'erreur - 4
            $strNotifications,
            // Enseignant - 5
            $strEnseignantOptions,
            // Division - 6
            $strDivOptions,
        );
        return $this->getRender($urlTemplate, $attributes);
    }
}
