<?php
namespace core\bean;

if (!defined('ABSPATH')) {
    die('Forbidden');
}
/**
 * Classe WpPageAdminAdulteDivisionBean
 * @author Hugues
 * @since 1.22.12.12
 * @version v2.23.01.02
 */
class WpPageAdminAdulteDivisionBean extends WpPageAdminAdulteBean
{
    public function __construct()
    {
        parent::__construct();
        
        /////////////////////////////////////////
        // Initialisation des variables
        $this->slugOnglet = self::ONGLET_PARENTS;
        $this->slugSubOnglet = self::SUBONGLET_PARENTS_DELEGUES;
        $this->titreOnglet = self::LABEL_PARENTS_DELEGUES;
        // Initialisation des données du bloc de présentation
        // Initialisation de la présence d'un bloc import
        $this->hasBlocImport = true;
        // Initialisation d'un éventuel objet dédié.
        $id = $this->initVar(self::ATTR_ID);
        $this->objAdulteDivision = $this->objAdulteDivisionServices->getAdulteDivisionById($id);
        // Initialisation de la pagination
        $this->curPage = $this->initVar(self::CST_CURPAGE, 1);
        // Initialisation des filtres
        $this->filtreAdherent = $this->initVar('filter-adherent', 'all');
        $this->filtreDivision = $this->initVar('filter-division', 'all');
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
     * @version v2.23.01.02
     */
    public function dealWithForm()
    {
        if (!isset($this->objAdulteDivision)) {
            return;
        }

        $strNotification = '';
        $strMessage = '';
        
        /////////////////////////////////////////
        // Un formulaire est soumis.
        // On récupère les données qu'on affecte à l'objet
        $this->objAdulteDivision->setField(self::FIELD_ADULTEID, $this->initVar(self::FIELD_ADULTEID));
        $this->objAdulteDivision->setField(self::FIELD_DIVISIONID, $this->initVar(self::FIELD_DIVISIONID));
        
        // Si le contrôle des données est ok
        if ($this->objAdulteDivision->controlerDonnees($strNotification, $strMessage)) {
            // Si l'id n'est pas défini
            if ($this->objAdulteDivision->getField(self::FIELD_ID)=='') {
                // On insère l'objet
                $this->objAdulteDivision->insert();
                // On renseigne le message d'information.
                $this->strNotifications = $this->getAlertContent(self::NOTIF_SUCCESS, self::MSG_SUCCESS_CREATE);
            } else {
                // On met à jour l'objet
                $this->objAdulteDivision->update();
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
     * @since v2.22.12.18
     * @version v2.22.12.18
     */
    public function getListElements($ids, $blnDelete=false)
    {
        $strElements = '';
        // On peut avoir une liste d'id en cas de suppression multiple.
        foreach (explode(',', $ids) as $id) {
            $objAdulteDivision = $this->objAdulteDivisionServices->getAdulteDivisionById($id);
            $strElements .= $this->getBalise(self::TAG_LI, $objAdulteDivision->getLibelle());
            if ($blnDelete) {
                $objAdulteDivision->delete();
            }
        }
        return $strElements;
    }
    
    /**
     * @return string
     * @since 2.22.12.18
     * @version 2.22.12.18
     */
    public function getSpecificHeaderRow()
    {
        $cellCenter = array(self::ATTR_CLASS=>'text-center');
        $trContent  = $this->getTh(self::LABEL_NOMPRENOM);
        $trContent .= $this->getTh(self::LABEL_DIVISIONS);
        $trContent .= $this->getTh(self::LABEL_MAIL, $cellCenter);
        return $trContent.$this->getTh(self::LABEL_ADHERENT, $cellCenter);
    }
    
    /**
     * @return string
     * @since 2.22.12.12
     * @version 2.22.12.12
     */
    public function getListContent()
    {
        $this->attrDescribeList = self::LABEL_LIST_PARENTS_DELEGUES;
        /////////////////////////////////////////
        // On va prendre en compte les éventuels filtres
        $attributes = array(
            self::FIELD_DELEGUE => 1,
        );
        // Filtre sur Division
        if ($this->filtreDivision!='' && $this->filtreDivision!='all') {
            $attributes[self::FIELD_DIVISIONID] = $this->filtreDivision;
        }
        // Filtre sur Adherent
        if ($this->filtreAdherent=='oui') {
            $attributes[self::FIELD_ADHERENT] = 1;
        } elseif ($this->filtreAdherent=='non') {
            $attributes[self::FIELD_ADHERENT] = 0;
        }
        // Sens d'affichage
        $sensTri = self::FIELD_LABELDIVISION;
        /////////////////////////////////////////
        // On va chercher les éléments à afficher
        $objItems = $this->objAdulteDivisionServices->getAdulteDivisionsWithFilters($attributes, $sensTri);
        /////////////////////////////////////////
        return $this->getDefaultListContent($objItems);
    }
    
    /**
     * Retourne le filtre spécifique à l'écran.
     * @return string
     * @since v2.22.12.18
     * @param v2.22.12.18
     */
    public function getTrFiltres()
    {
        /////////////////////////////////////////
        // Filtre en place
        $arrFilters = array(
            'division' => $this->filtreDivision,
            'adherent' => $this->filtreAdherent,
        );
        
        /////////////////////////////////////////
        // On va mettre en place la ligne de Filtre
        $trContent = '';
        if ($this->curUser->hasEditorRights()) {
            $trContent .= $this->getTh(self::CST_NBSP);
        }
        $trContent .= $this->getTh(self::CST_NBSP);
        $trContent .= $this->getFiltreDivision($arrFilters);
        $trContent .= $this->getTh(self::CST_NBSP);
        
        // Filtre Adhérent
        $trContent .= $this->getFiltreAdherent($arrFilters);
        
        if ($this->curUser->hasEditorRights()) {
            $trContent .= $this->getButtonFiltre();
        }
        return $this->getBalise(self::TAG_TR, $trContent);
    }
    
    public function getEditContent()
    {
        $baseUrl = $this->getUrl(array(self::CST_SUBONGLET=>''));
        return $this->objAdulteDivision->getBean()->getForm($baseUrl, $this->strNotifications);
    }
}
