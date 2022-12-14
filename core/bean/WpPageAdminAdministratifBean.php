<?php
namespace core\bean;

if (!defined('ABSPATH')) {
    die('Forbidden');
}
/**
 * Classe WpPageAdminAdministratifBean
 * @author Hugues
 * @since 1.22.12.01
 * @version 1.22.12.08
 */
class WpPageAdminAdministratifBean extends WpPageAdminBean
{
    public function __construct()
    {
        parent::__construct();
        // Initialisation des variables
        $this->slugOnglet = self::ONGLET_ADMINISTRATIFS;
        $this->titreOnglet = self::LABEL_ADMINISTRATIFS;

        $strNotification = '';
        $strMessage = '';
        
        $id = $this->initVar(self::ATTR_ID);
        $this->objAdministratif = $this->objAdministrationServices->getAdministrationById($id);

        /////////////////////////////////////////
        // Vérification de la soumission d'un formulaire
        $postAction = $this->initVar(self::CST_POST_ACTION);
        if ($postAction!='') {
            // Un formulaire est soumis.
            // On récupère les données qu'on affecte à l'objet
            $this->objAdministratif->setField(self::FIELD_GENRE, $this->initVar(self::FIELD_GENRE));
            $this->objAdministratif->setField(self::FIELD_NOMTITULAIRE, $this->initVar(self::FIELD_NOMTITULAIRE));
            $this->objAdministratif->setField(self::FIELD_LABELPOSTE, $this->initVar(self::FIELD_LABELPOSTE));
            
            // Si le contrôle des données est ok
            if ($this->objAdministratif->controlerDonnees($strNotification, $strMessage)) {
                // Si l'id n'est pas défini
                if ($id=='') {
                    // On insère l'objet
                    $this->objAdministratif->insert();
                } else {
                    // On met à jour l'objet
                    $this->objAdministratif->update();
                }
            } else {
                // TODO : Le contrôle de données n'est pas bon. Afficher l'erreur.
            }
            // TODO : de manière générale, ce serait bien d'afficher le résultat de l'opération.
        }
        /////////////////////////////////////////
        
        /////////////////////////////////////////
        // Construction du Breadcrumbs
        $urlElements = array(self::CST_ONGLET=>$this->slugOnglet);
        $buttonContent = $this->getLink($this->titreOnglet, $this->getUrl($urlElements), self::CST_TEXT_WHITE);
        $buttonAttributes = array(self::ATTR_CLASS=>self::CSS_BTN_DARK.' '.self::CSS_DISABLED);
        $this->breadCrumbsContent .= $this->getButton($buttonContent, $buttonAttributes);
        /////////////////////////////////////////
    }

    /**
     * @return string
     * @since 2.22.12.07
     * @version 2.22.12.07
     */
    public function getOngletContent()
    {
        $this->urlOngletContentTemplate = self::WEB_PPFC_PRES_ADM;
        return $this->getCommonOngletContent();
    }
    
    public function getDeleteContent()
    {
        $strElements = '';
        
        // On peut avoir une liste d'id en cas de suppression multiple.
        $ids = $this->initVar(self::ATTR_ID);
        foreach (explode(',', $ids) as $id) {
            $objAdministratif = $this->objAdministrationServices->getAdministrationById($id);
            $strElements .= $this->getBalise(self::TAG_LI, $objAdministratif->getFullInfo());
        }
        
        $urlElements = array(
            self::ATTR_ID => $ids,
            self::CST_CONFIRM => 1,
            self::CST_ACTION=>self::CST_DELETE,
        );
        $urlTemplate = self::WEB_PPFC_DEL_ADM;
        $attributes = array(
            // Liste des éléments supprimés
            $strElements,
            // Url de confirmation
            $this->getUrl($urlElements),
            // URl d'annulation
            $this->getUrl(),
        );
        return $this->getRender($urlTemplate, $attributes);
    }
    
    public function getDeletedContent()
    {
        $strElements = '';
        
        // On peut avoir une liste d'id en cas de suppression multiple.
        $ids = $this->initVar(self::ATTR_ID);
        foreach (explode(',', $ids) as $id) {
            $objAdministratif = $this->objAdministrationServices->getAdministrationById($id);
            $strElements .= $this->getBalise(self::TAG_LI, $objAdministratif->getFullInfo());
            $objAdministratif->delete();
        }
        
        $urlTemplate = self::WEB_PPFC_CONF_DEL;
        $attributes = array(
            // Liste des éléments supprimés
            $strElements,
            // URl d'annulation
            $this->getUrl(),
        );
        return $this->getRender($urlTemplate, $attributes);
    }
    
    /**
     * @return string
     * @since 2.22.12.07
     * @version 2.22.12.07
     */
    public function getEditContent()
    {
        $baseUrl = $this->getUrl(array(self::CST_SUBONGLET=>''));
        return $this->objAdministratif->getBean()->getForm($baseUrl);
    }
    
    /**
     * @param boolean $blnHasEditorRights
     * @return string
     * @since 2.22.12.07
     * @version 2.22.12.07
     */
    public function getListHeaderRow($blnHasEditorRights=false)
    {
        // Selon qu'on a les droit d'administration ou non, on n'aura pas autant de colonnes à afficher.
        // On veut afficher les éléments suivants (* si les droits d'édition) :
        // * une case à cocher
        // - un nom
        // - un poste
        // * des actions à effectuer
        $trContent = '';
        if ($blnHasEditorRights) {
            $trContent .= $this->getTh(self::CST_NBSP);
        }
        $trContent .= $this->getTh(self::LABEL_NOMTITULAIRE);
        $trContent .= $this->getTh(self::LABEL_LABELPOSTE);
        if ($blnHasEditorRights) {
            $trContent .= $this->getTh(self::LABEL_ACTIONS, array(self::ATTR_CLASS=>'text-center'));
        }
        return $this->getBalise(self::TAG_TR, $trContent);
    }
    
    /**
     * @return string
     * @since 2.22.12.07
     * @version 2.22.12.07
     */
    public function getListContent($blnHasEditorRights=false)
    {
        $this->attrDescribeList = self::LABEL_LIST_ADMINISTRATIFS;
        /////////////////////////////////////////
        // On va chercher les éléments à afficher
        $objItems = $this->objAdministrationServices->getAdministrationsWithFilters();
        /////////////////////////////////////////
        return $this->getDefaultListContent($objItems, $blnHasEditorRights);
    }
}
