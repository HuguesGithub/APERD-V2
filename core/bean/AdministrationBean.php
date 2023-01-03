<?php
namespace core\bean;

use core\domain\AdministrationClass;

if (!defined('ABSPATH')) {
    die('Forbidden');
}
/**
 * Classe AdministrationBean
 * @author Hugues
 * @since 2.22.12.05
 * @version v2.23.01.03
 */
class AdministrationBean extends LocalBean
{
    /**
     * Class Constructor
     * @param AdministrationClass $objAdministration
     * @since 2.22.12.05
     * @version 2.22.12.05
     */
    public function __construct($objAdministration='')
    {
        parent::__construct();
        $this->obj = ($objAdministration=='' ? new AdministrationClass() : $objAdministration);
    }
    
    //////////////////////////////////////////////////
    // METHODES
    //////////////////////////////////////////////////
    /**
     * @param boolean $blnHasEditorRights
     * @param string $baseUrl
     * @param boolean $blnChecked
     * @return string
     * @since 2.22.12.05
     * @version 2.22.12.07
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
            $this->baseUrl .= '?'.self::CST_ONGLET.'='.self::ONGLET_ADMINISTRATIFS;
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
            
        // Poste
        $trContent .= $this->getBalise(self::TAG_TD, $this->obj->getField(self::FIELD_LABELPOSTE), $attributes);
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
     * @since 2.22.12.05
     * @version v2.23.01.03
     */
    public function getForm($baseUrl, $strNotifications='')
    {
        $urlTemplate = self::WEB_A_FORM_ADMINISTRATION;
        $attributes = array(
            // Création - 1
            ($this->obj->getField(self::FIELD_ID)=='' ? self::LABEL_CREER : self::LABEL_MODIFIER),
            // Identifiant de l'Administration - 2
            $this->obj->getField(self::FIELD_ID),
            // Annuler - 3
            $baseUrl,
            // Notifications - 4
            $strNotifications,
            // Genre de l'Administration - 5
            $this->obj->getField(self::FIELD_GENRE),
            // Nom de l'Administration - 6
            $this->obj->getField(self::FIELD_NOMTITULAIRE),
            // Poste de l'Administration - 7
            $this->obj->getField(self::FIELD_LABELPOSTE),
        );
        return $this->getRender($urlTemplate, $attributes);
    }
}
