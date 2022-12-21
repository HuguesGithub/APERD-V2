<?php
namespace core\bean;

if (!defined('ABSPATH')) {
    die('Forbidden');
}
/**
 * Classe WpPageAdminAdulteBean
 * @author Hugues
 * @since 1.22.12.08
 * @version 1.22.12.08
 */
class WpPageAdminAdulteBean extends WpPageAdminBean
{
    public function __construct()
    {
        parent::__construct();
        
        /////////////////////////////////////////
        // Initialisation des variables
        $this->slugOnglet = self::ONGLET_PARENTS;
        $this->slugSubOnglet = $this->initVar(self::CST_SUBONGLET);
        $this->titreOnglet = self::LABEL_PARENTS;
        // Initialisation des données du bloc de présentation
        $this->hasPresentation = true;
        $this->strPresentationTitle = self::LABEL_PARENTS_ELEVES;
        $this->strPresentationContent = self::LABEL_INTERFACE_PARENTS_PRES;
        // Initialisation de la présence d'un bloc import
        $this->hasBlocImport = true;
        // Initialisation d'un éventuel objet dédié.
        $id = $this->initVar(self::ATTR_ID);
        $this->objAdulte = $this->objAdulteServices->getAdulteById($id);
        // Initialisation de la pagination
        $this->curPage = $this->initVar(self::CST_CURPAGE, 1);
        // Initialisation des filtres
        $this->filtreAdherent = $this->initVar('filter-adherent', 'all');
        /////////////////////////////////////////
        
        /////////////////////////////////////////
        // Construction du Breadcrumbs
        if ($this->slugSubOnglet=='') {
            $href = $this->getUrl();
            $buttonAttributes = array(self::ATTR_CLASS=>self::CSS_BTN_DARK.' '.self::CSS_DISABLED);
        } else {
            $urlElements = array(self::CST_ONGLET=>$this->slugOnglet, self::CST_SUBONGLET=>'');
            $href = $this->getUrl($urlElements);
            $buttonAttributes = array(self::ATTR_CLASS=>self::CSS_BTN_DARK);
        }
        $buttonContent = $this->getLink($this->titreOnglet, $href, self::CST_TEXT_WHITE);
        $this->breadCrumbsContent .= $this->getButton($buttonContent, $buttonAttributes);
        /////////////////////////////////////////
    }
    
    /**
     * En cas de formulaire, on le traite. A priori, Création ou édition pour l'heure
     * @since v2.22.12.21
     * @version v2.22.12.21
     */
    public function dealWithForm()
    {
        $strNotification = '';
        $strMessage = '';
        
        // Un formulaire est soumis.
        // On récupère les données qu'on affecte à l'objet
        $this->objAdulte->setField(self::FIELD_NOMADULTE, $this->initVar(self::FIELD_NOMADULTE));
        $this->objAdulte->setField(self::FIELD_PRENOMADULTE, $this->initVar(self::FIELD_PRENOMADULTE));
        $this->objAdulte->setField(self::FIELD_MAILADULTE, $this->initVar(self::FIELD_MAILADULTE));
        $this->objAdulte->setField(self::FIELD_ADHERENT, $this->initVar(self::FIELD_ADHERENT, 0));
        $strPhoneAdulte = str_replace(' ', '', $this->initVar(self::FIELD_PHONEADULTE));
        $this->objAdulte->setField(self::FIELD_PHONEADULTE, $strPhoneAdulte);
        
        // Si le contrôle des données est ok
        if ($this->objAdulte->controlerDonnees($strNotification, $strMessage)) {
            // Si l'id n'est pas défini
            if ($this->objAdulte->getField(self::FIELD_ID)=='') {
                // On insère l'objet
                $this->objAdulte->insert();
            } else {
                // On met à jour l'objet
                $this->objAdulte->update();
            }
        } else {
            // TODO : Le contrôle de données n'est pas bon. Afficher l'erreur.
        }
        // TODO : de manière générale, ce serait bien d'afficher le résultat de l'opération.
    }
    
    /**
     * @since v2.22.12.12
     * @version v2.22.12.12
     */
    public static function getStaticWpPageBean($slugSubContent)
    {
        if ($slugSubContent == self::CST_PARENTS_DELEGUES) {
            $objBean = new WpPageAdminAdulteDivisionBean();
        } else {
            $objBean = new WpPageAdminAdulteBean();
        }
        return $objBean;
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
            $objAdulte = $this->objAdulteServices->getAdulteById($id);
            $strElements .= $this->getBalise(self::TAG_LI, $objAdulte->getName());
            if ($blnDelete) {
                $objAdulte->delete();
            }
        }
        return $strElements;
    }
    
    /**
     * @return string
     * @since 2.22.12.07
     * @version 2.22.12.18
     */
    public function getSpecificHeaderRow()
    {
        $cellCenter = array(self::ATTR_CLASS=>'text-center');
        $trContent  = $this->getTh(self::LABEL_NOMPRENOM);
        $trContent .= $this->getTh(self::LABEL_MAIL);
        return $trContent.$this->getTh(self::LABEL_ADHERENT, $cellCenter);
    }
      
    /**
     * @return string
     * @since 2.22.12.07
     * @version 2.22.12.07
     */
    public function getListContent()
    {
        $this->attrDescribeList = self::LABEL_LIST_PARENTS;
        /////////////////////////////////////////
        // On va prendre en compte les éventuels filtres
        $attributes = array();
          
        if ($this->filtreAdherent=='oui') {
            $attributes[self::FIELD_ADHERENT] = 1;
        } elseif ($this->filtreAdherent=='non') {
            $attributes[self::FIELD_ADHERENT] = 0;
        }
        /////////////////////////////////////////
        // On va chercher les éléments à afficher
        $objItems = $this->objAdulteServices->getAdultesWithFilters($attributes);
        /////////////////////////////////////////
        return $this->getDefaultListContent($objItems);
    }
    
    /**
     * @return string
     * @since 2.22.12.07
     * @version 2.22.12.07
     */
    public function getEditContent()
    {
        $baseUrl = $this->getUrl(array(self::CST_SUBONGLET=>''));
        return $this->objAdulte->getBean()->getForm($baseUrl);
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
    
    /**
     * Retourne le filtre spécifique à l'écran.
     * @return string
     * @param v2.22.12.18
     * @since v2.22.12.19
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
        $trContent .= $this->getTh(self::CST_NBSP);
        
        // Filtre Adhérent
        $trContent .= $this->getFiltreAdherent();
        
        if ($this->curUser->hasEditorRights()) {
            $trContent .= $this->getButtonFiltre();
        }
        return $this->getBalise(self::TAG_TR, $trContent);
    }
    
    /**
     * Construction du Bouton Réinitialisation du filtre
     * @return string
     * @since v2.22.12.19
     * @version v2.22.12.19
     */
    public function getButtonFiltre()
    {
        $strIcon = $this->getIcon(self::I_FILTER_CIRCLE_XMARK);
        $strButton = $this->getButton($strIcon);
        $extraAttributes = array(self::ATTR_TITLE=>self::LABEL_CLEAR_FILTER);
        $strLink = $this->getLink($strButton, $this->getUrl(), '', $extraAttributes);
        $strDiv = $this->getDiv($strLink, array(self::ATTR_CLASS=>'row-actions text-center'));
        return $this->getTh($strDiv, array(self::ATTR_CLASS=>'column-actions'));
    }
    
    /**
     * Construction du Filtre Adherent
     * @return string
     * @since v2.22.12.18
     * @version v2.22.12.18
     */
    public function getFiltreAdherent()
    {
        $urlTemplate = self::WEB_PPF_FILTRE;
        
        // Définition du label en fonction d'un éventuel filtre courant.
        if ($this->filtreAdherent=='oui') {
            $label = 'Oui';
        } elseif ($this->filtreAdherent=='non') {
            $label = 'Non';
        } else {
            $label = 'Tous';
        }
        
        // Construction de la liste des options.
        $strOptions = '';
        $strClass = 'dropdown-item text-white';
        $baseUrl = $this->getUrl().self::CST_AMP.'filter-adherent=';
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
}
