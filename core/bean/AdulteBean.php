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
 * @version 2.22.12.08
 */
class AdulteBean extends LocalBean
{
    /**
     * Class Constructor
     * @param AdulteClass $objAdulte
     * @since 2.22.12.08
     * @version 2.22.12.08
     */
    public function __construct($objAdulte='')
    {
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
		
		///////////////////////////////////////////////:
		// Les cases à cocher
        if ($blnHasEditorRights) {
            $id = $this->obj->getField(self::FIELD_ID);
            
            $this->baseUrl  = '/'.self::PAGE_ADMIN;
            $this->baseUrl .= '?'.self::CST_ONGLET.'='.self::ONGLET_PARENTS;
            $this->baseUrl .= self::CST_AMP.self::FIELD_ID.'='.$id;
                        
            // Case à cocher
            $trContent  = $this->getCellInput($blnChecked);
		}
		///////////////////////////////////////////////:
		
		///////////////////////////////////////////////:
		// La partie commune
		// Nom sans le lien d'édition
		$label = $this->getBalise(self::TAG_STRONG, $this->obj->getName());
		$trContent .= $this->getBalise(self::TAG_TD, $label, $attributes);
		
		// Mail
		$trContent .= $this->getBalise(self::TAG_TD, $this->obj->getField(self::FIELD_MAILADULTE), $attributes);
		
		// Adhérent
		if ($this->obj->getField(self::FIELD_ADHERENT)==1) {
			$extra = 'btn-success';
			$strIcon = $this->getIcon(self::I_SQUARE_CHECK);
		} else {
			$extra = 'btn-danger';
			$strIcon = $this->getIcon(self::I_SQUARE_XMARK);
		}
		$strAdherent = $this->getButton($strIcon, array(self::ATTR_CLASS=>'disabled '.$extra));
		$adhAttributes = array(self::ATTR_CLASS=>self::CST_TEXT_WHITE.' text-center');
		$trContent .= $this->getBalise(self::TAG_TD, $strAdherent, $adhAttributes);
		///////////////////////////////////////////////:
		
		///////////////////////////////////////////////:
		// Les boutons d'action
        if ($blnHasEditorRights) {
            // Actions
            $trContent .= $this->getCellActions();
        }
		///////////////////////////////////////////////:



		else {
            $attributes = array(self::ATTR_CLASS=>self::CST_TEXT_WHITE);
            
            
        }
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