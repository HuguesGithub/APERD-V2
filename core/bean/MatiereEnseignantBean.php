<?php
namespace core\bean;

use core\domain\MatiereEnseignantClass;

if (!defined('ABSPATH')) {
    die('Forbidden');
}
/**
 * Classe MatiereEnseignantBean
 * @author Hugues
 * @since 2.22.12.12
 * @version 2.22.12.14
 */
class MatiereEnseignantBean extends LocalBean
{
    /**
     * Class Constructor
     * @param MatiereEnseignantClass $objAdulteDivision
     * @since 2.22.12.12
     * @version 2.22.12.14
     */
    public function __construct($objMatiereEnseignant='')
    {
        parent::__construct();
        $this->obj = ($objMatiereEnseignant=='' ? new MatiereEnseignantClass() : $objMatiereEnseignant);
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
            $this->baseUrl .= '?'.self::CST_ONGLET.'='.self::ONGLET_MATIERES;
            $this->baseUrl .= self::CST_AMP.self::CST_SUBONGLET.'='.self::CST_MATIERES_ENSEIGNANTS;
            $this->baseUrl .= self::CST_AMP.self::FIELD_ID.'='.$id;
            
            // Case à cocher
            $trContent  = $this->getCellInput($blnChecked);
        }
        ///////////////////////////////////////////////
        
        ///////////////////////////////////////////////
        // La partie commune
        // La Matière
        $label = $this->getBalise(self::TAG_STRONG, $this->obj->getMatiere()->getField(self::FIELD_LABELMATIERE));
        $trContent .= $this->getBalise(self::TAG_TD, $label, $attributes);

        // Nom sans le lien d'édition
        $label = $this->getBalise(self::TAG_STRONG, $this->obj->getEnseignant()->getName());
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
     * @since 2.22.12.28
     * @version v2.23.01.02
     */
    public function getForm($baseUrl, $strNotifications='')
    {
        ///////////////////////////////////////////////
        // Construction de la liste des matières
        $strMatiereOptions = $this->getBalise(self::TAG_OPTION);
        $objsMatiere = $this->objMatiereServices->getMatieresWithFilters();
        while (!empty($objsMatiere)) {
            $objMatiere = array_shift($objsMatiere);
            $matiereId = $objMatiere->getField(self::FIELD_ID);
            $matiereLabel = $objMatiere->getField(self::FIELD_LABELMATIERE);
            // Construction de la liste des options.
            $attributes = array(self::ATTR_VALUE=>$matiereId);
            if ($matiereId==$this->obj->getField(self::FIELD_MATIEREID)) {
                $attributes[self::ATTR_SELECTED] = ' '.self::ATTR_SELECTED;
            }
            $strMatiereOptions .= $this->getBalise(self::TAG_OPTION, $matiereLabel, $attributes);
        }
        ///////////////////////////////////////////////
        
        ///////////////////////////////////////////////
        // Construction de la liste des enseignants
        $strEnseignantOptions = $this->getBalise(self::TAG_OPTION);
        $objsEnseignant = $this->objEnseignantServices->getEnseignantsWithFilters();
        while (!empty($objsEnseignant)) {
            $objEnseignant = array_shift($objsEnseignant);
            $enseignantId = $objEnseignant->getField(self::FIELD_ID);
            $name = $objEnseignant->getName();
            // Construction de la liste des options.
            $attributes = array(self::ATTR_VALUE=>$enseignantId);
            if ($enseignantId==$this->obj->getField(self::FIELD_ENSEIGNANTID)) {
                $attributes[self::ATTR_SELECTED] = ' '.self::ATTR_SELECTED;
            }
            $strEnseignantOptions .= $this->getBalise(self::TAG_OPTION, $name, $attributes);
        }
        ///////////////////////////////////////////////
        
        $urlTemplate = self::WEB_A_FORM_MATIERE_ENSEIGNANT;
        $attributes = array(
            // Création - 1
            ($this->obj->getField(self::FIELD_ID)=='' ? self::LABEL_CREER : self::LABEL_MODIFIER),
            // Identifiant de l'Objet - 2
            $this->obj->getField(self::FIELD_ID),
            // Annuler - 3
            $baseUrl,
            // Message de confirmation ou d'erreur - 4
            $strNotifications,
            // Matiere - 5
            $strMatiereOptions,
            // Enseignant- 6
            $strEnseignantOptions,
        );
        return $this->getRender($urlTemplate, $attributes);
    }
}
