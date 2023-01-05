<?php
namespace core\bean;

if (!defined('ABSPATH')) {
    die('Forbidden');
}
/**
 * Classe WpPageAdminEnseignantPrincipalBean
 * @author Hugues
 * @since v2.23.01.02
 * @version v2.23.01.04
 */
class WpPageAdminEnseignantPrincipalBean extends WpPageAdminEnseignantBean
{
    public function __construct()
    {
        parent::__construct();

        /////////////////////////////////////////
        // Initialisation des variables
        $this->slugOnglet = self::ONGLET_ENSEIGNANTS;
        $this->slugSubOnglet = self::CST_ENSEIGNANTS_PRINCIPAUX;
        $this->titreOnglet = self::LABEL_ENSEIGNANTS_PRINCIPAUX;
        // Initialisation des données du bloc de présentation
        // Initialisation de la présence d'un bloc import
        $this->hasBlocImport = true;
        // Initialisation d'un éventuel objet dédié.
        $id = $this->initVar(self::ATTR_ID);
        $this->objEnseignantPrincipal = $this->objEnseignantPrincipalServices->getEnseignantPrincipalById($id);
        // Initialisation de la pagination
        $this->curPage = $this->initVar(self::CST_CURPAGE, 1);
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
     * @since v2.22.12.28
     * @version v2.22.12.28
     */
    public function dealWithForm()
    {
        if (!isset($this->objEnseignantPrincipal)) {
            return;
        }

        $strNotification = '';
        $strMessage = '';
        
        /////////////////////////////////////////
        // Un formulaire est soumis.
        // On récupère les données qu'on affecte à l'objet
        $this->objEnseignantPrincipal->setField(self::FIELD_ENSEIGNANTID, $this->initVar(self::FIELD_ENSEIGNANTID));
        $this->objEnseignantPrincipal->setField(self::FIELD_DIVISIONID, $this->initVar(self::FIELD_DIVISIONID));
        
        // Si le contrôle des données est ok
        if ($this->objEnseignantPrincipal->controlerDonnees($strNotification, $strMessage)) {
            // Si l'id n'est pas défini
            if ($this->objEnseignantPrincipal->getField(self::FIELD_ID)=='') {
                // On insère l'objet
                $this->objEnseignantPrincipal->insert();
                // On renseigne le message d'information.
                $this->strNotifications = $this->getAlertContent(self::NOTIF_SUCCESS, self::MSG_SUCCESS_CREATE);
            } else {
                // On met à jour l'objet
                $this->objEnseignantPrincipal->update();
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
            $objEnseignantPrincipal = $this->objEnseignantPrincipalServices->getEnseignantPrincipalById($id);
            $strElements .= $this->getBalise(self::TAG_LI, $objEnseignantPrincipal->getLibelle());
            if ($blnDelete) {
                $objEnseignantPrincipal->delete();
            }
        }
        return $strElements;
    }
    
    /**
     * @return string
     * @since v2.23.01.02
     * @version v2.23.01.02
     */
    public function getSpecificHeaderRow()
    {
        $trContent  = $this->getTh(self::LABEL_NOMPRENOM);
        return $trContent.$this->getTh(self::LABEL_PRINCIPAL);
    }
    
    /**
     * @return string
     * @since 2.22.12.12
     * @version 2.22.12.12
     */
    public function getListContent()
    {
        $this->attrDescribeList = self::LABEL_LIST_ENSEIGNANTS_PRINCIPAUX;
        /////////////////////////////////////////
        // On va prendre en compte les éventuels filtres
        $attributes = array();
        // Sens d'affichage
        $sensTri = self::FIELD_LABELDIVISION;

        /////////////////////////////////////////
        // On va chercher les éléments à afficher
        $objItems = $this->objEnseignantPrincipalServices->getEnseignantPrincipalsWithFilters($attributes, $sensTri);
        /////////////////////////////////////////
        return $this->getDefaultListContent($objItems);
    }
    
    public function getEditContent()
    {
        $baseUrl = $this->getUrl(array(self::CST_SUBONGLET=>''));
        return $this->objEnseignantPrincipal->getBean()->getForm($baseUrl, $this->strNotifications);
    }
}