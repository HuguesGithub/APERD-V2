<?php
namespace core\bean;

use core\domain\EnseignantClass;

if (!defined('ABSPATH')) {
    die('Forbidden');
}
/**
 * Classe EnseignantBean
 * @author Hugues
 * @since v2.23.01.02
 * @version v2.23.01.02
 */
class EnseignantBean extends LocalBean
{
    /**
     * Class Constructor
     * @param EnseignantClass $objEnseignant
     * @since v2.23.01.02
     * @version v2.23.01.02
     */
    public function __construct($objEnseignant='')
    {
        parent::__construct();
        // TODO : A supprimer quand l'édition sera faite.
        $this->hasEdit = false;
        $this->obj = ($objEnseignant=='' ? new EnseignantClass() : $objEnseignant);
    }
    
    //////////////////////////////////////////////////
    // METHODES
    //////////////////////////////////////////////////
    /**
     * @param boolean $blnHasEditorRights
     * @param string $baseUrl
     * @param boolean $blnChecked
     * @return string
     * @since v2.23.01.02
     * @version v2.23.01.02
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
            $this->baseUrl .= self::CST_AMP.self::FIELD_ID.'='.$id;
                        
            // Case à cocher
            $trContent  = $this->getCellInput($blnChecked);
        }
        ///////////////////////////////////////////////
        
        ///////////////////////////////////////////////
        // La partie commune
        // Genre
        $trContent .= $this->getBalise(self::TAG_TD, $this->obj->getField(self::FIELD_GENRE), $attributes);
        
        // Nom sans le lien d'édition
        $label = $this->getBalise(self::TAG_STRONG, $this->obj->getName());
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
     * @since v2.23.01.02
     * @version v2.23.01.02
     */
    public function getForm($baseUrl, $strNotifications='')
    {
        $urlTemplate = self::WEB_A_FORM_ENSEIGNANT;
        $attributes = array(
            // Création - 1
            ($this->obj->getField(self::FIELD_ID)=='' ? self::LABEL_CREER : self::LABEL_MODIFIER),
            // Identifiant de l'Adulte - 2
            $this->obj->getField(self::FIELD_ID),
            // Annuler - 3
            $baseUrl,
            // Alerte - 4
            $strNotifications,
            // Genre de l'Enseignant - 5
            $this->obj->getField(self::FIELD_GENRE),
            // Nom de l'Enseignant - 6
            $this->obj->getField(self::FIELD_NOMENSEIGNANT),
            // Prénom de l'Enseignant - 7
            $this->obj->getField(self::FIELD_PRENOMENSEIGNANT),
        );
        return $this->getRender($urlTemplate, $attributes);
    }
}
