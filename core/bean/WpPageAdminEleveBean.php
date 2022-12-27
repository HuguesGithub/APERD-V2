<?php
namespace core\bean;

use core\domain\MySQLClass;

if (!defined('ABSPATH')) {
    die('Forbidden');
}
/**
 * Classe WpPageAdminEleveBean
 * @author Hugues
 * @since 1.22.12.22
 * @version 1.22.12.22
 */
class WpPageAdminEleveBean extends WpPageAdminBean
{
    public function __construct()
    {
        parent::__construct();
        
        /////////////////////////////////////////
        // Initialisation des variables
        $this->slugOnglet = self::ONGLET_ELEVES;
        $this->titreOnglet = self::LABEL_ELEVES;
        // Initialisation des données du bloc de présentation
        $this->blnBoutonCreation = true;
        $this->hasPresentation = true;
        $this->strPresentationTitle = self::LABEL_ELEVES;
        $this->strPresentationContent = self::LABEL_INTERFACE_ELEVES_PRES;
        // Initialisation de la présence d'un bloc import
        $this->hasBlocImport = true;
        // Initialisation d'un éventuel objet dédié.
        $id = $this->initVar(self::ATTR_ID);
        $this->objEleve = $this->objEleveServices->getEleveById($id);
        // Initialisation de la pagination
        $this->curPage = $this->initVar(self::CST_CURPAGE, 1);
        // Initialisation des filtres
        $this->filtreDivision = $this->initVar('filter-division', 'all');
        $this->filtreDelegue = $this->initVar('filter-delegue', 'all');
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
     * @since v2.22.12.22
     * @version v2.22.12.22
     */
    public function dealWithForm()
    {
        $strNotification = '';
        $strMessage = '';
        
        /////////////////////////////////////////
        // Un formulaire est soumis.
        // On récupère les données qu'on affecte à l'objet
        $this->objEleve->setField(self::FIELD_NOMELEVE, $this->initVar(self::FIELD_NOMELEVE));
        $this->objEleve->setField(self::FIELD_PRENOMELEVE, $this->initVar(self::FIELD_PRENOMELEVE));
        $this->objEleve->setField(self::FIELD_DIVISIONID, $this->initVar(self::FIELD_DIVISIONID));
        $this->objEleve->setField(self::FIELD_DELEGUE, $this->initVar(self::FIELD_DELEGUE));
        
        // Si le contrôle des données est ok
        if ($this->objEleve->controlerDonnees($strNotification, $strMessage)) {
            // Si l'id n'est pas défini
            if ($this->objEleve->getField(self::FIELD_ID)=='') {
                // On insère l'objet
                $this->objEleve->insert();
                // On renseigne le message d'information.
                $this->strNotifications = $this->getAlertContent(self::NOTIF_SUCCESS, self::MSG_SUCCESS_CREATE);
            } else {
                // On met à jour l'objet
                $this->objEleve->update();
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
     * @since v2.22.12.22
     * @version v2.22.12.22
     */
    public function getListElements($ids, $blnDelete=false)
    {
        $strElements = '';
        // On peut avoir une liste d'id en cas de suppression multiple.
        foreach (explode(',', $ids) as $id) {
            $objEleve = $this->objEleveServices->getEleveById($id);
            $strElements .= $this->getBalise(self::TAG_LI, $objEleve->getName());
            if ($blnDelete) {
                $objEleve->delete();
            }
        }
        return $strElements;
    }
    
    /**
     * @return string
     * @since 2.22.12.22
     * @version 2.22.12.23
     */
    public function getSpecificHeaderRow()
    {
        $cellCenter = array(self::ATTR_CLASS=>'text-center');
        $trContent  = $this->getTh(self::LABEL_NOMPRENOM);
        $trContent .= $this->getTh(self::LABEL_DIVISIONS, $cellCenter);
        return $trContent.$this->getTh(self::LABEL_DELEGUE, $cellCenter);
    }
    
    /**
     * @return string
     * @since 2.22.12.22
     * @version 2.22.12.22
     */
    public function getListContent()
    {
        $this->attrDescribeList = self::LABEL_LIST_ELEVES;
        /////////////////////////////////////////
        // On va prendre en compte les éventuels filtres
        $attributes = array();
        
        // Filtre sur Division
        if ($this->filtreDivision!='' && $this->filtreDivision!='all') {
            $attributes[self::FIELD_DIVISIONID] = $this->filtreDivision;
        }
        // Filtre sur Délégué
        if ($this->filtreDelegue=='oui') {
            $attributes[self::FIELD_DELEGUE] = 1;
        } elseif ($this->filtreDelegue=='non') {
            $attributes[self::FIELD_DELEGUE] = 0;
        }
        /////////////////////////////////////////
        // On va chercher les éléments à afficher
        $objItems = $this->objEleveServices->getElevesWithFilters($attributes);
        /////////////////////////////////////////
        return $this->getDefaultListContent($objItems);
    }
    
    /**
     * Retourne le filtre spécifique à l'écran.
     * @return string
     * @since v2.22.12.23
     * @version v2.22.12.23
     */
    public function getTrFiltres()
    {
        /////////////////////////////////////////
        // Filtre en place
        $arrFilters = array(
            'division' => $this->filtreDivision,
        );
        
        /////////////////////////////////////////
        // On va mettre en place la ligne de Filtre
        $trContent = '';
        if ($this->curUser->hasEditorRights()) {
            $trContent .= $this->getTh(self::CST_NBSP);
        }
        $trContent .= $this->getTh(self::CST_NBSP);
        $trContent .= $this->getFiltreDivision($arrFilters);
        
        // Filtre Delegue
        $trContent .= $this->getFiltreDelegue();
        
        if ($this->curUser->hasEditorRights()) {
            $trContent .= $this->getButtonFiltre();
        }
        return $this->getBalise(self::TAG_TR, $trContent);
    }
    
    /**
     * Construction du Filtre Délégué
     * @return string
     * @since v2.22.12.23
     * @version v2.22.12.23
     */
    public function getFiltreDelegue()
    {
        $urlTemplate = self::WEB_PPF_FILTRE;
        
        // Définition du label en fonction d'un éventuel filtre courant.
        if ($this->filtreDelegue=='oui') {
            $label = 'Oui';
        } elseif ($this->filtreDelegue=='non') {
            $label = 'Non';
        } else {
            $label = 'Tous';
        }
        
        // Construction de la liste des options.
        $strOptions = '';
        $strClass = 'dropdown-item text-white';
        $baseUrl = $this->getUrl().self::CST_AMP.'filter-Delegue=';
        $strOptions .= $this->getBalise(self::TAG_LI, $this->getLink('Oui', $baseUrl.'oui', $strClass));
        $strOptions .= $this->getBalise(self::TAG_LI, $this->getLink('Non', $baseUrl.'non', $strClass));
        $strOptions .= $this->getBalise(self::TAG_LI, $this->getLink('Tous', $baseUrl.'all', $strClass));
        
        // Définition des attributs pour le template
        $attributes = array(
            $label,
            $strOptions,
        );
        
        return $this->getRender($urlTemplate, $attributes);
    }
    
    /**
     * @return string
     * @since 2.22.12.22
     * @version 2.22.12.22
     */
    public function getEditContent()
    {
        $baseUrl = $this->getUrl(array(self::CST_SUBONGLET=>''));
        return $this->objEleve->getBean()->getForm($baseUrl, $this->strNotifications);
    }
    
    /**
     * Retourne les éléments visibles dans la dropdown de Download
     * @return string
     * @since v2.22.12.18
     * @version v2.22.12.18
     */
    public function getDownloadUls()
    {
        $ulContent  = $this->getLiDropdown('Sélection', 'dropdownDownload');
        $ulContent .= $this->getLiDropdown('Filtre', 'dropdownDownload');
        return $ulContent.$this->getLiDropdown('Tous', 'dropdownDownload');
    }
}
