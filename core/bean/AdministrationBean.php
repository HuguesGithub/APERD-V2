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
 * @version 2.22.12.08
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
        if ($blnHasEditorRights) {
            $id = $this->obj->getField(self::FIELD_ID);
            
            $this->baseUrl  = '/'.self::PAGE_ADMIN;
            $this->baseUrl .= '?'.self::CST_ONGLET.'='.self::ONGLET_ADMINISTRATIFS;
            $this->baseUrl .= self::CST_AMP.self::FIELD_ID.'='.$id;
                        
            $attributes = array(self::ATTR_CLASS=>self::CST_TEXT_WHITE);
            // Case à cocher
            $trContent  = $this->getCellInput($blnChecked);
            
            // Nom + Lien d'édition
            $urlEdition = $this->baseUrl.self::CST_AMP.self::CST_SUBONGLET.'='.self::CST_EDIT;
            $aLink = $this->getLink($this->obj->getName(), $urlEdition, self::CST_TEXT_WHITE.' row-title');
            $label = $this->getBalise(self::TAG_STRONG, $aLink);
            $trContent .= $this->getBalise(self::TAG_TD, $label, $attributes);
            
            // Poste
            $trContent .= $this->getBalise(self::TAG_TD, $this->obj->getField(self::FIELD_LABELPOSTE), $attributes);
            
            // Actions
            $trContent .= $this->getCellActions();
        } else {
            $attributes = array(self::ATTR_CLASS=>self::CST_TEXT_WHITE);
            
            // Nom
            $label = $this->getBalise(self::TAG_STRONG, $this->obj->getName());
            $trContent  = $this->getBalise(self::TAG_TD, $label, $attributes);
            
            // Poste
            $trContent .= $this->getBalise(self::TAG_TD, $this->obj->getField(self::FIELD_LABELPOSTE), $attributes);
        }
        return $this->getBalise(self::TAG_TR, $trContent, $attributes);
    }
    
    /**
     * @param string $baseUrl
     * @return string
     * @since 2.22.12.05
     * @version 2.22.12.05
     */
    public function getForm($baseUrl)
    {
        $urlTemplate = self::WEB_A_FORM_ADMINISTRATION;
        $attributes = array(
            // Création - 1
            ($this->obj->getField(self::FIELD_ID)=='' ? self::LABEL_CREER : self::LABEL_MODIFIER),
            // Identifiant de l'Administration - 2
            $this->obj->getField(self::FIELD_ID),
            // Annuler - 3
            $baseUrl,
            // Genre de l'Administration - 4
            $this->obj->getField(self::FIELD_GENRE),
            // Nom de l'Administration - 5
            $this->obj->getField(self::FIELD_NOMTITULAIRE),
            // Poste de l'Administration - 6
            $this->obj->getField(self::FIELD_LABELPOSTE),
        );
        return $this->getRender($urlTemplate, $attributes);
    }
}
