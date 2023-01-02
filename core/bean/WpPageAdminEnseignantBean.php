<?php
namespace core\bean;

if (!defined('ABSPATH')) {
    die('Forbidden');
}
/**
 * Classe WpPageAdminEnseignantBean
 * @author Hugues
 * @since v2.23.01.02
 * @version v2.23.01.02
 */
class WpPageAdminEnseignantBean extends WpPageAdminBean
{
    public function __construct()
    {
        parent::__construct();
        
        /////////////////////////////////////////
        // Initialisation des variables
        $this->slugOnglet = self::ONGLET_ENSEIGNANTS;
        $this->slugSubOnglet = $this->initVar(self::CST_SUBONGLET);
        $this->titreOnglet = self::LABEL_ENSEIGNANTS;
        // Initialisation des données du bloc de présentation
        $this->blnBoutonCreation = false;// TODO true;
        $this->hasPresentation = true;
        $this->strPresentationTitle = self::LABEL_ENSEIGNANTS;
        $this->strPresentationContent = self::LABEL_INTERFACE_ENSEIGNANTS_PRES;
        // Initialisation de la présence d'un bloc import
        $this->hasBlocImport = false;// TODO true;
        // Initialisation d'un éventuel objet dédié.
        $id = $this->initVar(self::ATTR_ID);
        $this->objEnseignant = $this->objEnseignantServices->getEnseignantById($id);
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
        /*
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
                // On renseigne le message d'information.
                $this->strNotifications = $this->getAlertContent(self::NOTIF_SUCCESS, self::MSG_SUCCESS_CREATE);
            } else {
                // On met à jour l'objet
                $this->objAdulte->update();
                // On renseigne le message d'information.
                $this->strNotifications = $this->getAlertContent(self::NOTIF_SUCCESS, self::MSG_SUCCESS_EDIT);
            }
        } else {
            // Le contrôle de données n'est pas bon. Afficher l'erreur.
            $this->strNotifications = $this->getAlertContent($strNotification, $strMessage);
        }
        /////////////////////////////////////////
         * 
         */
    }
    
    /**
     * @since v2.23.01.02
     * @version v2.23.01.02
     */
    public static function getStaticWpPageBean($slugSubContent)
    {
        if ($slugSubContent == self::CST_ENSEIGNANTS_PRINCIPAUX) {
            $objBean = new WpPageAdminEnseignantPrincipalBean();
        } else {
            $objBean = new WpPageAdminEnseignantBean();
        }
        return $objBean;
    }
      
    /**
     * Construction d'une liste d'éléments dont les identifiants sont passés en paramètre.
     * Si $blnDelete est à true, on en profite pour effacer l'élément.
     * @param int|string $ids
     * @param boolean $blnDelete
     * @return string
     * @since v2.23.01.02
     * @version v2.23.01.02
     */
    public function getListElements($ids, $blnDelete=false)
    {
        $strElements = '';
        // On peut avoir une liste d'id en cas de suppression multiple.
        foreach (explode(',', $ids) as $id) {
            $objEnseignant = $this->objEnseignantServices->getEnseignantById($id);
            $strElements .= $this->getBalise(self::TAG_LI, $objEnseignant->getName());
            if ($blnDelete) {
                $objEnseignant->delete();
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
        $trContent  = $this->getTh(self::LABEL_GENRE);
        return $trContent.$this->getTh(self::LABEL_NOMPRENOM);
    }
      
    /**
     * @return string
     * @since v2.23.01.02
     * @version v2.23.01.02
     */
    public function getListContent()
    {
        $this->attrDescribeList = self::LABEL_LIST_ENSEIGNANTS;
        /////////////////////////////////////////
        // On va prendre en compte les éventuels filtres
        $attributes = array();
        /////////////////////////////////////////
        // On va chercher les éléments à afficher
        $objItems = $this->objEnseignantServices->getEnseignantsWithFilters($attributes);
        /////////////////////////////////////////
        return $this->getDefaultListContent($objItems);
    }
    
    /**
     * @return string
     * @since v2.23.01.02
     * @version v2.23.01.02
     */
    public function getEditContent()
    {
        $baseUrl = $this->getUrl(array(self::CST_SUBONGLET=>''));
        return $this->objEnseignant->getBean()->getForm($baseUrl, $this->strNotifications);
    }
    
    /**
     * Retourne les éléments visibles dans la dropdown de Download
     * @return string
     * @since v2.23.01.02
     * @version v2.23.01.02
     */
    public function getDownloadUls()
    {
        $ulContent  = $this->getLiDropdown('Sélection', 'dropdownDownload');
        return $ulContent.$this->getLiDropdown('Tous', 'dropdownDownload');
    }
    
}
