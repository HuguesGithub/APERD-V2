<?php
namespace core\bean;

use core\domain\AdulteClass;

if (!defined('ABSPATH')) {
    die('Forbidden');
}
/**
 * Classe AdulteBean
 * @author Hugues
 * @since 2.22.12.08
 * @version 2.22.12.14
 */
class AdulteBean extends LocalBean
{
    /**
     * Class Constructor
     * @param AdulteClass $objAdulte
     * @since 2.22.12.08
     * @version 2.22.12.14
     */
    public function __construct($objAdulte='')
    {
        parent::__construct();
        $this->obj = ($objAdulte=='' ? new AdulteClass() : $objAdulte);
    }
    
    //////////////////////////////////////////////////
    // METHODES
    //////////////////////////////////////////////////
    /**
     * @param boolean $blnHasEditorRights
     * @param string $baseUrl
     * @param boolean $blnChecked
     * @return string
     * @since 2.22.12.08
     * @version 2.22.12.08
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
        
        // Mail
        $trContent .= $this->getBalise(self::TAG_TD, $this->obj->getField(self::FIELD_MAILADULTE), $attributes);
        
        // Adhérent
        $blnChecked = ($this->obj->getField(self::FIELD_ADHERENT)==1);
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
     * @since 2.22.12.08
     * @version 2.22.12.08
     */
    public function getForm($baseUrl)
    {
        $urlTemplate = self::WEB_A_FORM_ADULTE;
        $attributes = array(
            // Création - 1
            ($this->obj->getField(self::FIELD_ID)=='' ? self::LABEL_CREER : self::LABEL_MODIFIER),
            // Identifiant de l'Adulte - 2
            $this->obj->getField(self::FIELD_ID),
            // Annuler - 3
            $baseUrl,
            // Nom du Parent - 4
            $this->obj->getField(self::FIELD_NOMADULTE),
            // Prénom du Parent - 5
            $this->obj->getField(self::FIELD_PRENOMADULTE),
            // Mail du Parent - 6
            $this->obj->getField(self::FIELD_MAILADULTE),
            // Parent Adhérent ? - 7
            ($this->obj->getField(self::FIELD_ADHERENT)==1 ? ' '.self::CST_CHECKED : ''),
            // Téléphone du Parent - 8
            $this->obj->getField(self::FIELD_PHONEADULTE),
        );
        return $this->getRender($urlTemplate, $attributes);
    }
}
