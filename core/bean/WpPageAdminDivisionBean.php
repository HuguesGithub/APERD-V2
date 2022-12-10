<?php
namespace core\bean;

if (!defined('ABSPATH')) {
    die('Forbidden');
}
/**
 * Classe WpPageAdminDivisionBean
 * @author Hugues
 * @since 1.22.12.11
 * @version 1.22.12.11
 */
class WpPageAdminDivisionBean extends WpPageAdminBean
{
    public function __construct()
    {
        parent::__construct();
        // Initialisation des variables
        $this->slugOnglet = self::ONGLET_DIVISIONS;
        $this->titreOnglet = self::LABEL_DIVISIONS;
        
        $strNotification = '';
        $strMessage = '';
        
        $id = $this->initVar(self::ATTR_ID);
        $this->objDivision = $this->objDivisionServices->getDivisionById($id);
        $this->curPage = $this->initVar(self::CST_CURPAGE, 1);
        
        /////////////////////////////////////////
        // Vérification de la soumission d'un formulaire
        $postAction = $this->initVar(self::CST_POST_ACTION);
        if ($postAction!='') {
            // Un formulaire est soumis.
            // On récupère les données qu'on affecte à l'objet
            $this->objDivision->setField(self::FIELD_LABELDIVISION, $this->initVar(self::FIELD_LABELDIVISION));

            // Si le contrôle des données est ok
            if ($this->objDivision->controlerDonnees($strNotification, $strMessage)) {
                // Si l'id n'est pas défini
                if ($id=='') {
                    // On insère l'objet
                    $this->objDivision->insert();
                } else {
                    // On met à jour l'objet
                    $this->objDivision->update();
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
     * @since 2.22.12.11
     * @version 2.22.12.11
     */
    public function getOngletContent()
    {
        $this->urlOngletContentTemplate = self::WEB_PPFC_PRES_DIVISION;
        return $this->getCommonOngletContent();
    }

    /**
     * @return string
     * @since 2.22.12.11
     * @version 2.22.12.11
     */
    public function getDeleteContent()
    {
        $strElements = '';
        
        // On peut avoir une liste d'id en cas de suppression multiple.
        $ids = $this->initVar(self::ATTR_ID);
        foreach (explode(',', $ids) as $id) {
            $objDivision = $this->objDivisionServices->getDivisionById($id);
            $strElements .= $this->getBalise(self::TAG_LI, $objDivision->getField(self::FIELD_LABELDIVISION));
        }
        
        $urlElements = array(
            self::ATTR_ID => $ids,
            self::CST_CONFIRM => 1,
        );
        $urlTemplate = self::WEB_PPFC_DEL_DIV;
        $attributes = array(
            // Liste des éléments supprimés
            $strElements,
            // Url de confirmation
            $this->getUrl($urlElements),
            // URl d'annulation
            $this->getUrl(array(self::CST_SUBONGLET=>'')),
        );
        return $this->getRender($urlTemplate, $attributes);
    }
    
    /**
     * @return string
     * @since 2.22.12.11
     * @version 2.22.12.11
     */
    public function getDeletedContent()
    {
        $strElements = '';
        
        // On peut avoir une liste d'id en cas de suppression multiple.
        $ids = $this->initVar(self::ATTR_ID);
        foreach (explode(',', $ids) as $id) {
            $objDivision = $this->objDivisionServices->getDivisionById($id);
            $strElements .= $this->getBalise(self::TAG_LI, $objDivision->getField(self::FIELD_LABELDIVISION));
            $objDivision->delete();
        }
        
        $urlTemplate = self::WEB_PPFC_CONF_DEL;
        $attributes = array(
            // Liste des éléments supprimés
            $strElements,
            // URl d'annulation
            $this->getUrl(array(self::CST_SUBONGLET=>'')),
        );
        return $this->getRender($urlTemplate, $attributes);
    }
    
    /**
     * @return string
     * @since 2.22.12.11
     * @version 2.22.12.11
     */
    public function getEditContent()
    {
        $baseUrl = $this->getUrl(array(self::CST_SUBONGLET=>''));
        return $this->objDivision->getBean()->getForm($baseUrl);
    }
    
    /**
     * @param boolean $blnHasEditorRights
     * @return string
     * @since 2.22.12.11
     * @version 2.22.12.11
     */
    public function getListHeaderRow($blnHasEditorRights=false)
    {
        // Selon qu'on a les droit d'administration ou non, on n'aura pas autant de colonnes à afficher.
        $trContent = '';
        if ($blnHasEditorRights) {
            $trContent .= $this->getTh(self::CST_NBSP);
        }
        $trContent .= $this->getTh(self::LABEL_LABELDIVISION);
        if ($blnHasEditorRights) {
            $trContent .= $this->getTh(self::LABEL_ACTIONS, array(self::ATTR_CLASS=>'text-center'));
        }
        return $this->getBalise(self::TAG_TR, $trContent);
    }
    
    /**
     * @return string
     * @since 2.22.12.11
     * @version 2.22.12.11
     */
    public function getListContent($blnHasEditorRights=false)
    {
        $this->attrDescribeList = self::LABEL_LIST_DIVISIONS;
        /////////////////////////////////////////
        // On va chercher les éléments à afficher
        $objItems = $this->objDivisionServices->getDivisionsWithFilters();
        /////////////////////////////////////////
        return $this->getDefaultListContent($objItems, $blnHasEditorRights);
    }
}
