<?php
namespace core\bean;

if (!defined('ABSPATH')) {
    die('Forbidden');
}
/**
 * Classe WpPageAdminAdulteDivisionBean
 * @author Hugues
 * @since 1.22.12.12
 * @version 1.22.12.12
 */
class WpPageAdminAdulteDivisionBean extends WpPageAdminAdulteBean
{
    public function __construct()
    {
        parent::__construct();
        // Initialisation des variables
        $this->slugOnglet = self::ONGLET_PARENTS;
        $this->slugSubOnglet = self::SUBONGLET_PARENTS_DELEGUES;
        $this->titreOnglet = self::LABEL_PARENTS_DELEGUES;
        $this->blnBoutonCreation = false;
        
        // Initialisation des templates
        $this->urlOngletContentTemplate = '';
        
        $strNotification = '';
        $strMessage = '';
        
        $id = $this->initVar(self::ATTR_ID);
        $this->objAdulteDivision = $this->objAdulteDivisionServices->getAdulteDivisionById($id);
        $this->curPage = $this->initVar(self::CST_CURPAGE, 1);
        $this->filtreAdherent = $this->initVar('filter-adherent', 'all');
        
        /////////////////////////////////////////
        // Vérification de la soumission d'un formulaire
        $postAction = $this->initVar(self::CST_POST_ACTION);
        if ($postAction!='') {
            // Un formulaire est soumis.
            // On récupère les données qu'on affecte à l'objet
            $this->objAdulteDivision->setField(self::FIELD_ADULTEID, $this->initVar(self::FIELD_ADULTEID));
            $this->objAdulteDivision->setField(self::FIELD_DIVISIONID, $this->initVar(self::FIELD_DIVISIONID));
            $this->objAdulteDivision->setField(self::FIELD_DELEGUE, 1);
            // Si le contrôle des données est ok
            if ($this->objAdulteDivision->controlerDonnees($strNotification, $strMessage)) {
                // Si l'id n'est pas défini
                if ($id=='') {
                    // On insère l'objet
                    $this->objAdulteDivision->insert();
                } else {
                    // On met à jour l'objet
                    $this->objAdulteDivision->update();
                }
            } else {
                // TODO : Le contrôle de données n'est pas bon. Afficher l'erreur.
            }
            // TODO : de manière générale, ce serait bien d'afficher le résultat de l'opération.
        }
        /////////////////////////////////////////
        
        /////////////////////////////////////////
        // Construction du Breadcrumbs
        $buttonContent = $this->getLink($this->titreOnglet, '#', self::CST_TEXT_WHITE);
        $buttonAttributes = array(self::ATTR_CLASS=>self::CSS_BTN_DARK.' '.self::CSS_DISABLED);
        $this->breadCrumbsContent .= $this->getButton($buttonContent, $buttonAttributes);
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
     * TODO : A implémenter plus proprement
     * @return string
     * @param v2.22.12.18
     * @since v2.22.12.18
     */
    public function getTrFiltres()
    {
        /////////////////////////////////////////
        // On va mettre en place la ligne de Filtre
        $trContent = '';
        if ($this->curUser->hasEditorRights()) {
            $trContent .= $this->getTh(self::CST_NBSP);
        }
        $trContent .= $this->getTh(self::CST_NBSP);
        $trContent .= $this->getFiltreDivision();
        $trContent .= $this->getTh(self::CST_NBSP);
        
        // Filtre Adhérent
        $trContent .= $this->getFiltreAdherent();
        
        if ($this->curUser->hasEditorRights()) {
            $strIcon = $this->getIcon(self::I_FILTER_CIRCLE_XMARK);
            $strButton = $this->getButton($strIcon);
            $extraAttributes = array(self::ATTR_TITLE=>self::LABEL_CLEAR_FILTER);
            $strLink = $this->getLink($strButton, $this->getUrl(), '', $extraAttributes);
            $strDiv = $this->getDiv($strLink, array(self::ATTR_CLASS=>'row-actions text-center'));
            $trContent .= $this->getTh($strDiv, array(self::ATTR_CLASS=>'column-actions'));
        }
        return $this->getBalise(self::TAG_TR, $trContent);
    }
    
    public function getFiltreDivision()
    {
        $urlTemplate = self::WEB_PPF_FILTRE;
        $label = 'Tous';
        $strOptions = '';
        $baseUrl = $this->getUrl().self::CST_AMP.'filter-division=';
        $strClass = 'dropdown-item text-white';
        
        $objsDivision = $this->objDivisionServices->getDivisionsWithFilters();
        while (!empty($objsDivision)) {
            $objDivision = array_shift($objsDivision);
            $strDiv = $objDivision->getField(self::FIELD_ID);
            // Définition du label en fonction d'un éventuel filtre courant.
            if ($this->filtreDivision==$strDiv) {
                $label = $objDivision->getField(self::FIELD_LABELDIVISION);
            }
            // Construction de la liste des options.
            $liContent = $this->getLink($objDivision->getField(self::FIELD_LABELDIVISION), $baseUrl.$strDiv, $strClass);
            $strOptions .= $this->getBalise(self::TAG_LI, $liContent);
        }
        $strOptions .= $this->getBalise(self::TAG_LI, $this->getLink('Tous', $baseUrl.'all', $strClass));
        
        // Définition des attributs pour le template
        $attributes = array(
            $label,
            $strOptions,
        );
        
        return $this->getRender($urlTemplate, $attributes);
    }
}
