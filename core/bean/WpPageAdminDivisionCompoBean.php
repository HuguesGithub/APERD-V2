<?php
namespace core\bean;

if (!defined('ABSPATH')) {
    die('Forbidden');
}
/**
 * Classe WpPageAdminDivisionCompoBean
 * @author Hugues
 * @since v2.23.01.01
 * @version v2.23.01.05
 */
class WpPageAdminDivisionCompoBean extends WpPageAdminDivisionBean
{
    public function __construct()
    {
        parent::__construct();

        /////////////////////////////////////////
        // Initialisation des variables
        $this->slugOnglet = self::ONGLET_DIVISIONS;
        $this->slugSubOnglet = self::CST_COMPOSITION_DIVISIONS;
        $this->titreOnglet = self::LABEL_COMPOSITION;
        // Initialisation des données du bloc de présentation
        // Initialisation de la présence d'un bloc import
        // Initialisation d'un éventuel objet dédié.
        $id = $this->initVar(self::ATTR_ID);
        $this->objDivisionComposition = $this->objDivisionCompositionServices->getDivisionCompositionById($id);
        // Initialisation de la pagination
        // Initialisation des filtres
        /////////////////////////////////////////
        
        /////////////////////////////////////////
        // Vérification de la soumission d'un formulaire
        if ($this->curUser->hasEditorRights() && $this->postAction!='') {
            $this->dealWithForm();
        }
        /////////////////////////////////////////
        
        /////////////////////////////////////////
        // Construction du Breadcrumbs
        $this->buildBreadCrumbs();
        /////////////////////////////////////////
    }
    
    /**
     * En cas de formulaire, on le traite. A priori, Création ou édition pour l'heure
     * @since v2.23.01.05
     * @version v2.23.01.05
     */
    public function dealWithForm()
    {
        if (!isset($this->objDivisionComposition)) {
            return;
        }
        
        $strNotification = '';
        $strMessage = '';
        
        /////////////////////////////////////////
        // Un formulaire est soumis.
        // On récupère les données qu'on affecte à l'objet
        $this->objDivisionComposition->setField(self::FIELD_DIVISIONID, $this->initVar(self::FIELD_DIVISIONID));
        $matiereEnseignantId = $this->initVar(self::FIELD_MATIEREENSEIGNANTID);
        $this->objDivisionComposition->setField(self::FIELD_MATIEREENSEIGNANTID, $matiereEnseignantId);
        
        // Si le contrôle des données est ok
        if ($this->objDivisionComposition->controlerDonnees($strNotification, $strMessage)) {
            // Si l'id n'est pas défini
            if ($this->objDivisionComposition->getField(self::FIELD_ID)=='') {
                // On insère l'objet
                $this->objDivisionComposition->insert();
                // On renseigne le message d'information.
                $this->strNotifications = $this->getAlertContent(self::NOTIF_SUCCESS, self::MSG_SUCCESS_CREATE);
            } else {
                // On met à jour l'objet
                $this->objDivisionComposition->update();
                // On renseigne le message d'information.
                $this->strNotifications = $this->getAlertContent(self::NOTIF_SUCCESS, self::MSG_SUCCESS_EDIT);
            }
        } else {
            // Le contrôle de données n'est pas bon. Afficher l'erreur.
            $this->strNotifications = $this->getAlertContent($strNotification, $strMessage);
        }
        /////////////////////////////////////////
    }
    
    /**
     * Construction d'une liste d'éléments dont les identifiants sont passés en paramètre.
     * Si $blnDelete est à true, on en profite pour effacer l'élément.
     * @param int|string $ids
     * @param boolean $blnDelete
     * @return string
     * @since v2.23.01.05
     * @version v2.23.01.05
     */
    public function getListElements($ids, $blnDelete=false)
    {
        $strElements = '';
        // On peut avoir une liste d'id en cas de suppression multiple.
        foreach (explode(',', $ids) as $id) {
            $objDivisionComposition = $this->objDivisionCompositionServices->getDivisionCompositionById($id);
            $strElements .= $this->getBalise(self::TAG_LI, $objDivisionComposition->getLibelle());
            if ($blnDelete) {
                $objDivisionComposition->delete();
            }
        }
        return $strElements;
    }
    
    /**
     * @return string
     * @since v2.23.01.01
     * @version v2.23.01.01
     */
    public function getSpecificHeaderRow()
    {
        $cellCenter = array(self::ATTR_CLASS=>'text-center');
        $trContent  = $this->getTh(self::LABEL_DIVISIONS);
        $trContent .= $this->getTh(self::LABEL_MATIERES);
        $trContent .= $this->getTh(self::LABEL_ENSEIGNANTS);
        return $trContent.$this->getTh(self::LABEL_PRINCIPAL, $cellCenter);
    }
    
    /**
     * @return string
     * @since v2.23.01.03
     * @version v2.23.01.03
     */
    public function getListContent()
    {
        $this->attrDescribeList = self::LABEL_LIST_DIVISIONS_COMPOSITIONS;
        /////////////////////////////////////////
        // On va prendre en compte les éventuels filtres
        $attributes = array();
        
        /////////////////////////////////////////
        // On va chercher les éléments à afficher
        $objItems = $this->objDivisionCompositionServices->getDivisionCompositionsWithFilters($attributes);
        /////////////////////////////////////////
        return $this->getDefaultListContent($objItems);
    }
    
    /**
     * @return string
     * @since v2.23.01.05
     * @version v2.23.01.05
     */
    public function getEditContent()
    {
        $baseUrl = $this->getUrl(array(self::CST_SUBONGLET=>''));
        return $this->objDivisionComposition->getBean()->getForm($baseUrl, $this->strNotifications);
    }
}
