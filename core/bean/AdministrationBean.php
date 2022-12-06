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
 * @version 2.22.12.05
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
     * @param string $baseUrl
     * @param boolean $checked
     * @return string
     * @since 2.22.12.05
     * @version 2.22.12.05
     */
    public function getRow($baseUrl, $checked=false)
    {
        if (self::isAdmin()) {
            $baseUrl .= self::CST_AMP.self::FIELD_ID.'='.$this->obj->getField(self::FIELD_ID);
            $urlTemplate = self::WEB_A_ROW_ADMINISTRATION;
            $attributes = array(
                // Identifiant de l'Administration - 1
                $this->obj->getField(self::FIELD_ID),
                // Url d'édition de l'Administration
                $baseUrl.self::CST_AMP.self::CST_ACTION.'='.self::CST_EDIT,
                // Genre de l'Administration - 3
                $this->obj->getField(self::FIELD_GENRE),
                // Nom de l'Administration - 4
                $this->obj->getField(self::FIELD_NOMTITULAIRE),
                // Poste de l'Administration - 5
                $this->obj->getField(self::FIELD_LABELPOSTE),
                // Url de suppression de l'Administration - 6
                $baseUrl.self::CST_AMP.self::CST_ACTION.'='.self::CST_DELETE,
                // Checkée ou non - 7
                ($checked ? self::CST_BLANK.self::CST_CHECKED : ''),
            );
        } else {
            $urlTemplate = self::WEB_P_ROW_ADMINISTRATION;
            $attributes = array(
                // Genre de l'Administration - 1
                $this->obj->getField(self::FIELD_GENRE),
                // Nom de l'Administration - 2
                $this->obj->getField(self::FIELD_NOMTITULAIRE),
                // Poste de l'Administration - 3
                $this->obj->getField(self::FIELD_LABELPOSTE),
            );
        }
        return $this->getRender($urlTemplate, $attributes);
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
            ($this->obj->getField(self::FIELD_ID)=='' ? self::LABEL_CREATION : self::LABEL_EDITION),
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
