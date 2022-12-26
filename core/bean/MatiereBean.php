<?php
namespace core\bean;

use core\domain\MatiereClass;

if (!defined('ABSPATH')) {
    die('Forbidden');
}
/**
 * Classe DivisionBean
 * @author Hugues
 * @since 2.22.12.21
 * @version 2.22.12.21
 */
class MatiereBean extends LocalBean
{
    /**
     * Class Constructor
     * @param MatiereClass $objMatiere
     * @since 2.22.12.21
     * @version 2.22.12.21
     */
    public function __construct($objMatiere='')
    {
        parent::__construct();
        $this->obj = ($objMatiere=='' ? new MatiereClass() : $objMatiere);
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
     * @since 2.22.12.21
     * @version 2.22.12.21
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
            $this->baseUrl .= self::CST_AMP.self::FIELD_ID.'='.$id;
                        
            // Case à cocher
            $trContent  = $this->getCellInput($blnChecked);
        }
        ///////////////////////////////////////////////
        
        ///////////////////////////////////////////////
        // La partie commune
        // Libellé sans le lien d'édition
        $label = $this->getBalise(self::TAG_STRONG, $this->obj->getField(self::FIELD_LABELMATIERE));
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
     * @since 2.22.12.21
     * @version 2.22.12.21
     */
    public function getForm($baseUrl, $strNotifications='')
    {
        $urlTemplate = self::WEB_A_FORM_MATIERE;
        $attributes = array(
            // Création - 1
            ($this->obj->getField(self::FIELD_ID)=='' ? self::LABEL_CREER : self::LABEL_MODIFIER),
            // Identifiant de la Matière - 2
            $this->obj->getField(self::FIELD_ID),
            // Annuler - 3
            $baseUrl,
            // Message de confirmation ou d'erreur - 4
            $strNotifications,
            // Libellé de la Matière - 5
            $this->obj->getField(self::FIELD_LABELMATIERE),
        );
        return $this->getRender($urlTemplate, $attributes);
    }
}
