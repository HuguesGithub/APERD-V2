<?php
namespace core\bean;

use core\domain\AdulteClass;

if (!defined('ABSPATH')) {
    die('Forbidden');
}
/**
 * Classe WpPageAdminBean
 * @author Hugues
 * @since 2.22.12.05
 * @version v2.23.01.02
 */
class WpPageAdminBean extends WpPageBean
{
    public $slugPage;
    public $slugOnglet;
    public $slugSubOnglet;
    
    public $arrSubOnglets = array();
    /**
     * Class Constructor
     * @since 2.22.12.05
     * @version 2.22.12.07
     */
    public function __construct()
    {
        parent::__construct();
        
        $this->slugPage = self::PAGE_ADMIN;
        $this->slugOnglet = $this->initVar(self::CST_ONGLET);
        $this->slugSubOnglet = $this->initVar(self::CST_SUBONGLET);
        $this->slugAction = $this->initVar(self::CST_ACTION);
        $this->filtreAdherent = $this->initVar('filter-adherent');
        $this->filtreDelegue  = $this->initVar('filter-delegue');
        $this->filtreDivision = $this->initVar('filter-division');
        $this->blnBoutonCreation = false;
        $this->hasPresentation = false;
        $this->strPresentationTitle = '';
        $this->strPresentationContent = '';
        $this->hasBlocImport = false;
        
        // Initialisation des templates
        $this->urlOngletContentTemplate = '';
        $this->urlDeleteTemplate = self::WEB_PPFC_DELETE;
        $this->urlDeleteConfirmTemplate = self::WEB_PPFC_CONF_DEL;
        // Initialisation de la variable de formulaire
        $this->postAction = $this->initVar(self::CST_POST_ACTION);
        $this->strNotifications = '';
        
        ////////////////////////////////////////////////////////
        // Gestion de l'identification via le formulaire
        if (isset($_POST['mail'])) {
            $this->dealWithLogin();
        } elseif (isset($_GET['logout'])) {
            // On cherche a priori à se déconnecter
            unset($_SESSION[self::SESSION_APERD_ID]);
        }
        ////////////////////////////////////////////////////////
        
        ////////////////////////////////////////////////////////
        // On va enrichir le profil Wordpress des infos dédiées APERD
        if (isset($_SESSION[self::SESSION_APERD_ID])) {
            // On navigue sur le site en étant identifié.
            $sqlAttributes = array(self::FIELD_MAILADULTE=>$_SESSION[self::SESSION_APERD_ID]);
            $objsAdulte = $this->objAdulteServices->getAdultesWithFilters($sqlAttributes);
            if (count($objsAdulte)==1) {
                $this->curUser = array_shift($objsAdulte);
            } else {
                $this->curUser = new AdulteClass();
            }
        } else {
            $this->curUser = new AdulteClass();
        }
        ////////////////////////////////////////////////////////
        
        ////////////////////////////////////////////////////////
        // Définition de la sidebar
        $this->arrSidebarContent = array(
            self::ONGLET_DESK => array(
                self::CST_ICON  => self::I_DESKTOP,
                self::CST_LABEL => self::LABEL_BUREAU,
            ),
            self::ONGLET_ADMINISTRATIFS => array(
                self::CST_ICON  => self::I_USERS,
                self::CST_LABEL => self::LABEL_ADMINISTRATIFS,
            ),
            self::ONGLET_DIVISIONS => array(
                self::CST_ICON  => self::I_SCHOOL,
                self::CST_LABEL => self::LABEL_DIVISIONS,
                self::CST_CHILDREN => array(
                    self::CST_COMPOSITION_DIVISIONS => self::LABEL_COMPOSITION,
                ),
            ),
            self::ONGLET_MATIERES => array(
                self::CST_ICON  => self::I_CHALKBOARD,
                self::CST_LABEL => self::LABEL_MATIERES,
                self::CST_CHILDREN => array(
                    self::CST_MATIERES_ENSEIGNANTS => self::LABEL_MATIERES_ENSEIGNANTS,
                ),
            ),
            self::ONGLET_ELEVES => array(
                self::CST_ICON  => self::I_USER_GRADUATE,
                self::CST_LABEL => self::LABEL_ELEVES,
            ),
            self::ONGLET_PARENTS => array(
                self::CST_ICON  => self::I_USERS,
                self::CST_LABEL => self::LABEL_PARENTS,
                self::CST_CHILDREN => array(
                    self::CST_PARENTS_DIVISIONS => self::LABEL_PARENTS_DELEGUES,
                ),
            ),
            self::ONGLET_ENSEIGNANTS => array(
                self::CST_ICON  => self::I_USERS,
                self::CST_LABEL => self::LABEL_ENSEIGNANTS,
                self::CST_CHILDREN => array(
                    self::CST_ENSEIGNANTS_PRINCIPAUX => self::LABEL_ENSEIGNANTS_PRINCIPAUX,
                ),
            ),
        );
        ////////////////////////////////////////////////////////
        
        ////////////////////////////////////////////////////////
        // Définition du premier élément du Breadcrumbs
        // Le lien vers la Home
        $aContent = $this->getIcon(self::I_DESKTOP);
        $buttonContent = $this->getLink($aContent, $this->getPageUrl(), self::CST_TEXT_WHITE);
        if ($this->slugOnglet==self::ONGLET_DESK || $this->slugOnglet=='') {
            $buttonAttributes = array(self::ATTR_CLASS=>self::CSS_BTN_DARK.' '.self::CSS_DISABLED);
        } else {
            $buttonAttributes = array(self::ATTR_CLASS=>self::CSS_BTN_DARK);
        }
        $this->breadCrumbsContent = $this->getButton($buttonContent, $buttonAttributes);
        /////////////////////////////////////////
    }
    
    public function dealWithLogin()
    {
        // On cherche a priori à se logguer
        $userMail = $this->initVar('mail');
        $userPassword = $this->initVar('password');
        // On s'identifie avec un user Wordpress.
        $wpUser = wp_authenticate_email_password(null, $userMail, $userPassword);
        // Si on a un wpUser qui correspond au mail et au mot de passe.
        if ($wpUser==null || get_class($wpUser)=='WP_Error') {
            // On a raté l'identification, on va essayé d'afficher la popup d'erreur.
            $_SESSION[self::SESSION_APERD_ID] = self::CST_ERR_LOGIN;
        } else {
            $_SESSION[self::SESSION_APERD_ID] = $_POST['mail'];
        }
    }
    
    /**
     * @return string
     * @since 2.22.12.07
     * @version 2.22.12.07
     */
     public function getPageUrl()
     { return '/'.$this->slugPage; }
     
    /**
     * @return string
     * @since 2.22.12.05
     * @version v2.23.01.02
     */
    public function getContentPage()
    {
        /////////////////////////////////////////////////////////////
        // Pour le cas où on n'est pas loggué, on court-circuite tout le processus d'affichage
        // pour afficher la page de garde avec la mire d'identification.
        if (!self::isAperdLogged()) {
            $urlTemplate = self::WEB_PPFS_CONNEXION_PANEL;
            // Si la variable de Session est renseigné à err_login, on a foiré l'identification
            if (isset($_SESSION[self::SESSION_APERD_ID]) && $_SESSION[self::SESSION_APERD_ID]==self::CST_ERR_LOGIN) {
                $strNotification  = self::MSG_ERREUR_CONTROL_IDENTIFICATION;
                unset($_SESSION[self::SESSION_APERD_ID]);
            } else {
                $strNotification = '';
            }
            $attributes = array(
                ($strNotification=='' ? 'd-none' : ''),
                $strNotification,
            );
            return $this->getRender($urlTemplate, $attributes);
        }
        /////////////////////////////////////////////////////////////
        
        try {
            switch ($this->slugOnglet) {
                case self::ONGLET_ADMINISTRATIFS :
                    $objBean = new WpPageAdminAdministratifBean();
                    break;
                case self::ONGLET_DIVISIONS :
                    $objBean = WpPageAdminDivisionBean::getStaticWpPageBean($this->slugSubOnglet);
                    break;
                case self::ONGLET_ELEVES :
                    $objBean = new WpPageAdminEleveBean();
                    break;
                case self::ONGLET_ENSEIGNANTS :
                    $objBean = WpPageAdminEnseignantBean::getStaticWpPageBean($this->slugSubOnglet);
                    break;
                case self::ONGLET_MATIERES :
                    $objBean = WpPageAdminMatiereBean::getStaticWpPageBean($this->slugSubOnglet);
                    break;
                case self::ONGLET_PARENTS :
                    $objBean = WpPageAdminAdulteBean::getStaticWpPageBean($this->slugSubOnglet);
                    break;
                case self::ONGLET_DESK   :
                default       :
                    $objBean = $this;
                    break;
            }
            $returned = $objBean->getBoard();
        } catch (\Exception $Exception) {
            $returned = 'Error';
        }
        return $returned;
    }
    /**
     * Retourne le contenu de l'interface
     * @return string
     * @since 2.22.12.05
     * @version 2.22.12.07
     */
    public function getBoard()
    {
        // On est loggué, on affiche le bureau.
        $urlTemplate = self::WEB_PP_BOARD;
        $attributes = array(
            // La barre de navigation
            $this->getNavigationBar(),
            // Le nom
            $this->curUser->getName(),
            // La sidebar
            $this->getSideBar(),
            // Header
            $this->getContentHeader(),
            // Le contenu de l'onglet
            $this->getOngletContent(),
            // Version
            self::VERSION,
            //
            'https://v2-aperd.jhugues.fr',
        );
        return $this->getRender($urlTemplate, $attributes);
    }
    
    /**
     * @return string
     * @since 2.22.12.07
     * @version 2.22.12.07
     */
    public function getNavigationBar()
    {
        $urlTemplate = self::WEB_PPFN_NAV_BAR;
        $attributes = array(
            // On va juste ajouter déventuels icones
            '',
        );
        return $this->getRender($urlTemplate, $attributes);
    }
    
    /**
     * @return string
     * @since 2.22.12.07
     * @version 2.22.12.07
     */
    public function getContentHeader()
    {
        $urlTemplate = self::WEB_PPFS_CONTENT_HEADER;
        $attributes = array(
            // Le Titre
            '',
            // Le BreadCrumb
            $this->getDiv($this->breadCrumbsContent, array(self::ATTR_CLASS=>'btn-group float-sm-right')),
        );
        return $this->getRender($urlTemplate, $attributes);
    }
     
    /**
     * @return string
     * @since 2.22.12.07
     * @version 2.22.12.28
     */
    public function getSideBar()
    {
        $urlTemplate = self::WEB_PPFN_NAV_SIDEBAR;
        $sidebarContent = '';
        foreach ($this->arrSidebarContent as $strOnglet => $arrOnglet) {
            // Le lien a-t-il des enfants ?
            $hasChildren = isset($arrOnglet[self::CST_CHILDREN]);
            
            // Mise en évidence de l'élément du menu sélectionné.
            $blnMenuOpen = ($strOnglet==$this->slugOnglet &&
                isset($arrOnglet[self::CST_CHILDREN][$this->slugSubOnglet]));
            $blnMenuActive = (!$blnMenuOpen &&
                ($this->slugOnglet==$strOnglet || $this->slugOnglet=='' && $strOnglet==self::ONGLET_DESK));
            
            // Construction du label
            $pContent  = $arrOnglet[self::CST_LABEL];
            $pContent .= ($hasChildren ? $this->getIcon(self::I_ANGLE_LEFT, self::CST_RIGHT) : '');
            // Construction du lien
            $urlElements = array(
                self::CST_ONGLET => $strOnglet,
                self::CST_SUBONGLET => '',
            );
            $aContent  = $this->getIcon($arrOnglet[self::CST_ICON], 'nav-icon');
            $aContent .= $this->getBalise(self::TAG_P, $pContent);
            $aAttributes = array(
                self::ATTR_HREF  => $this->getUrl($urlElements),
                self::ATTR_CLASS => 'nav-link'.($blnMenuActive ? ' '.self::CST_ACTIVE : ''),
            );
            $superLiContent = $this->getBalise(self::TAG_A, $aContent, $aAttributes);
            
            // S'il a des enfants, on enrichit
            if ($hasChildren) {
                $superLiContent .= $this->getSidebarChildren($arrOnglet, $strOnglet);
            }
            
            // Construction de l'élément de la liste
            $liAttributes = array(self::ATTR_CLASS=>'nav-item'.($blnMenuOpen ? ' menu-open' : ''));
            $sidebarContent .= $this->getBalise(self::TAG_LI, $superLiContent, $liAttributes);
        }
        
        $attributes = array(
            $sidebarContent,
        );
        return $this->getRender($urlTemplate, $attributes);
    }
    
    /**
     * @param array $arrOnglet
     * @param string $strOnglet
     * @return string
     * @since v2.22.12.28
     * @version v2.22.12.28
     */
    public function getSidebarChildren($arrOnglet, $strOnglet)
    {
        $ulContent = '';
        foreach ($arrOnglet[self::CST_CHILDREN] as $strSubOnglet => $label) {
            $aContent  = $this->getIcon(self::I_CIRCLE, 'nav-icon').$this->getBalise(self::TAG_P, $label);
            $urlElements = array(
                self::CST_ONGLET => $strOnglet,
                self::CST_SUBONGLET => $strSubOnglet,
            );
            $strIsActive = ($strSubOnglet==$this->slugSubOnglet ? ' '.self::CST_ACTIVE : '');
            $aAttributes = array(
                self::ATTR_HREF  => $this->getUrl($urlElements),
                self::ATTR_CLASS => 'nav-link'.$strIsActive,
            );
            $liContent = $this->getBalise(self::TAG_A, $aContent, $aAttributes);
            $ulContent .= $this->getBalise(self::TAG_LI, $liContent, array(self::ATTR_CLASS=>'nav-item'));
        }
        $liAttributes = array(self::ATTR_CLASS=>'nav nav-treeview');
        return $this->getBalise(self::TAG_UL, $ulContent, $liAttributes);
    }
    
    /**
     * @param array $urlElements
     * @return string
     * @since 2.22.12.07
     * @version 2.22.12.07
     */
    public function getUrl($urlElements=array())
    {
        $url = $this->getPageUrl();
        /////////////////////////////////////////////
        // Si l'onglet est passé en paramètre et qu'il est défini, on va le reprendre
        // S'il est défini et vide, on va l'enlever.
        // S'il n'est pas défini, on va mettre l'onglet courant par défaut.
        if (!isset($urlElements[self::CST_ONGLET])) {
            $url .= '?'.self::CST_ONGLET.'='.$this->slugOnglet;
        } else {
            $url .= '?'.self::CST_ONGLET.'='.$urlElements[self::CST_ONGLET];
            unset($urlElements[self::CST_ONGLET]);
        }
        /////////////////////////////////////////////
     
        /////////////////////////////////////////////
        // On fait de même avec le subOnglet
        if (!isset($urlElements[self::CST_SUBONGLET]) && $this->slugSubOnglet!='') {
            $url .= self::CST_AMP.self::CST_SUBONGLET.'='.$this->slugSubOnglet;
        } elseif (isset($urlElements[self::CST_SUBONGLET]) && $urlElements[self::CST_SUBONGLET]!='') {
            $url .= self::CST_AMP.self::CST_SUBONGLET.'='.$urlElements[self::CST_SUBONGLET];
            unset($urlElements[self::CST_SUBONGLET]);
        }
        /////////////////////////////////////////////
     
        /////////////////////////////////////////////
        // Maintenant, on doit ajouter ceux passés en paramètre
        if (!empty($urlElements)) {
            foreach ($urlElements as $key => $value) {
                if ($value!='') {
                    $url .= self::CST_AMP.$key.'='.$value;
                }
            }
        }
        /////////////////////////////////////////////
     
        return $url;
     }
    
    /**
     * @return string
     * @since 2.22.12.08
     * @version 2.22.12.08
     */
    public function getOngletContent()
    {
        // Définition des droits de l'utilisateur
        $blnHasEditorRights = (self::isAdmin() || $this->curUser->getField(self::FIELD_ROLEADULTE)>=self::ROLE_EDITEUR);
        $blnIsEditorPage    = ($this->slugAction==self::CST_WRITE);
        $blnIsDeletePage    = ($this->slugAction==self::CST_DELETE);
        $blnConfirm         = $this->initVar(self::CST_CONFIRM, false);
        $strColeft          = '';
        
        ///////////////////////////////////////////////
        // Définition éventuelle du bouton Création / Annulation
        if ($blnHasEditorRights && $this->blnBoutonCreation) {
            $strColeft .= $this->getButtonCreationAnnulation();
        }
        ///////////////////////////////////////////////

        ///////////////////////////////////////////////
        // Définition éventuelle du block de présentation
        if ($this->hasPresentation) {
            $urlCardTemplate = self::WEB_PPFC_CARD;
            $attributes = array(
                $this->strPresentationTitle,
                $this->strPresentationContent,
            );
            $strColeft .= $this->getRender($urlCardTemplate, $attributes);
        }
        ///////////////////////////////////////////////

        ///////////////////////////////////////////////
        // Définition éventuelle du block d'import
        if ($this->hasBlocImport) {
            $url = self::WEB_PPFC_UPLOAD;
            $impAttributes = ($this->slugSubOnglet=='' ? array($this->slugOnglet) : array($this->slugSubOnglet));
            $strColeft .= $this->getRender($url, $impAttributes);
            $strColeft .= $this->getDiv('', array(self::ATTR_ID=>'alertBlock'));
        }
        ///////////////////////////////////////////////
            
        // Définition du contenu de la page.
        $strMainContent = '';
        if ($this->slugOnglet!='' && $this->slugOnglet!=self::ONGLET_DESK) {
            // Si on a les droits et on est sur une page d'édition
            if ($blnHasEditorRights) {
                if ($blnIsEditorPage) {
                    // Interface d'édition
                    $strMainContent = $this->getEditContent();
                } elseif ($blnIsDeletePage) {
                    if ($blnConfirm) {
                        // Interface de suppression
                        $strMainContent = $this->getDeletedContent();
                    } else {
                        // Interface de suppression
                        $strMainContent = $this->getDeleteContent();
                    }
                } else {
                    // Interface de liste
                    $strMainContent = $this->getListContent();
                    
                }
            } else {
                // Interface de liste
                $strMainContent = $this->getListContent();
            }
        }
        
        
        $urlTemplate = self::WEB_PPFS_CONTENT_ONE_4TH;
        $attributes = array(
            // Identifiant de la page
            $this->slugOnglet,
            // La colonne de gauche
            $strColeft,
            // Une liste d'administratifs ou un formulaire d'édition.
            $strMainContent,
        );
        return $this->getRender($urlTemplate, $attributes);
    }
    
    /**
     * Permet d'accéder à l'écran de création d'un élément
     * @return string
     * @since 2.22.12.18
     * @version 2.22.12.18
     */
    public function getCreateButton()
    {
        $label = $this->getIcon(self::I_EDIT).self::CST_NBSP.self::LABEL_CREER_ENTREE;
        $href = $this->getUrl(array(self::CST_ACTION=>self::CST_WRITE));
        return $this->getLinkedButton($label, $href);
    }
    
    /**
     * Permet de retourner à l'écran par défaut
     * @return string
     * @since 2.22.12.18
     * @version 2.22.12.18
     */
    public function getReturnButton()
    {
        $label = $this->getIcon(self::I_ANGLES_LEFT).self::CST_NBSP.self::LABEL_RETOUR;
        return $this->getLinkedButton($label, $this->getUrl());
    }
    
    /**
     * Permet d'annuler l'action en cours et de retourner à l'écran par défaut
     * @return string
     * @since 2.22.12.18
     * @version 2.22.12.18
     */
    public function getCancelButton()
    {
        $label = $this->getIcon(self::I_ANGLES_LEFT).self::CST_NBSP.self::LABEL_ANNULER;
        return $this->getLinkedButton($label, $this->getUrl());
    }
    
    /**
     * Retourne un bouton avec un lien
     * @return string
     * @since 2.22.12.18
     * @version 2.22.12.18
     */
    public function getLinkedButton($label, $href)
    {
        $btnAttributes = array(self::ATTR_CLASS=>'btn btn-primary mb-3 btn-block');
        $strButton = $this->getButton($label, $btnAttributes);
        return $this->getLink($strButton, $href, '');
    }
    
    /**
     * Affiche la liste des éléments à supprimer et demande confirmation.
     * @return string
     * @since 2.22.12.18
     * @version 2.22.12.18
     */
    public function getDeleteContent()
    {
        // Récupération des ids à supprimer
        $ids = $this->initVar(self::ATTR_ID);
        // Url de confirmation
        $urlElements = array(
            self::ATTR_ID => $ids,
            self::CST_CONFIRM => 1,
            self::CST_ACTION=>self::CST_DELETE,
        );
        // Construction du tableau d'attributs pour le template
        $attributes = array(
            // Liste des éléments à supprimer
            $this->getListElements($ids),
            // Url de confirmation
            $this->getUrl($urlElements),
            // URl d'annulation
            $this->getUrl(),
        );
        return $this->getRender($this->urlDeleteTemplate, $attributes);
    }
    
    /**
     * Affiche la liste des éléments supprimés.
     * @return string
     * @since 2.22.12.18
     * @version 2.22.12.18
     */
    public function getDeletedContent()
    {
        // Récupération des ids à supprimer
        $ids = $this->initVar(self::ATTR_ID);
        // Construction du tableau d'attributs pour le template
        $attributes = array(
            // Liste des éléments supprimés
            $this->getListElements($ids, true),
            // URl d'annulation
            $this->getUrl(),
        );
        return $this->getRender($this->urlDeleteConfirmTemplate, $attributes);
    }
    
    /**
     * @return string
     * @since 2.22.12.12
     * @version 2.22.12.12
     */
    public function getListHeaderRow()
    {
        $cellCenter = array(self::ATTR_CLASS=>'text-center');
        // Selon qu'on a les droit d'administration ou non, on n'aura pas autant de colonnes à afficher.
        $trContent = '';
        if ($this->curUser->hasEditorRights()) {
            $trContent .= $this->getTh(self::CST_NBSP);
        }
        $trContent .= $this->getSpecificHeaderRow();
        if ($this->curUser->hasEditorRights()) {
            $trContent .= $this->getTh(self::LABEL_ACTIONS, $cellCenter);
        }
        return $this->getBalise(self::TAG_TR, $trContent);
    }
    
    /**
     * @return string
     * @since 2.22.12.07
     * @version 2.22.12.07
     */
    public function getDefaultListContent($objItems)
    {
        /////////////////////////////////////////
        // Pagination
        $this->blnHasPagination = false;
        $this->strPagination = $this->buildPagination($objItems);
        /////////////////////////////////////////
        $strContent = $this->getTrFiltres();
        while (!empty($objItems)) {
            $objItem = array_shift($objItems);
            $strContent .= $objItem->getBean()->getRow($this->curUser->hasEditorRights());
        }
        /////////////////////////////////////////
        
        $attributes = array(
            // On défini le titre
            $this->titreOnglet,
            // On défini un éventuel entête/footer de boutons d'actions
            $this->getListControlTools(),
            // On défini le tag de la liste
            $this->slugOnglet,
            // On défini la description de la liste
            $this->attrDescribeList,
            // On défini le header de la liste
            $this->getListHeaderRow(),
            // On défini le contenu de la liste
            $strContent,
        );
        return $this->getRender(self::WEB_PPFC_LIST_DEFAULT, $attributes);
    }
    
    /**
     * @return string
     * @since 2.22.12.07
     * @version 2.22.12.08
     */
    public function getListControlTools()
    {
        $divContent = '';
        
        // Si on a les droits, on ajoute le bouton de download
        if ($this->curUser->hasEditorRights()) {
            $divContent .= $this->getDownloadButton();
        }
        
        // On ajoute le div de pagination, s'il y a lieu
        if ($this->blnHasPagination) {
            $divContent .= $this->getDiv($this->strPagination, array(self::ATTR_CLASS=>'float-right'));
        }
        
        $divAttributes = array(self::ATTR_CLASS=>$this->slugOnglet.'-controls toolbox-controls');
        return $this->getDiv($divContent, $divAttributes);
    }
    
    /**
     * @param array $objs
     * @return string
     * @since 2.22.12.08
     * @version 2.22.12.08
     */
    public function buildPagination(&$objs)
    {
        $nbItems = count($objs);
        $nbItemsPerPage = 10;
        $nbPages = ceil($nbItems/$nbItemsPerPage);
        $strPagination = '';
        if ($nbPages>1) {
            $this->blnHasPagination = true;
            // Le bouton page précédente
            $label = $this->getIcon(self::I_CARET_LEFT);
            if ($this->curPage!=1) {
                $btnClass = '';
                $href = $this->getUrl(array(self::CST_CURPAGE=>$this->curPage-1));
                $btnContent = $this->getLink($label, $href, self::CST_TEXT_WHITE);
            } else {
                $btnClass = self::CST_DISABLED.' '.self::CST_TEXT_WHITE;
                $btnContent = $label;
            }
            $btnAttributes = array(self::ATTR_CLASS=>$btnClass);
            $strPagination .= $this->getButton($btnContent, $btnAttributes).self::CST_NBSP;
            
            // La chaine des éléments affichés
            $firstItem = ($this->curPage-1)*$nbItemsPerPage;
            $lastItem = min(($this->curPage)*$nbItemsPerPage, $nbItems);
            $strPagination .= vsprintf(self::DYN_DISPLAYED_PAGINATION, array($firstItem+1, $lastItem, $nbItems));
            
            // Le bouton page suivante
            $label = $this->getIcon(self::I_CARET_RIGHT);
            if ($this->curPage!=$nbPages) {
                $btnClass = '';
                $href = $this->getUrl(array(self::CST_CURPAGE=>$this->curPage+1));
                $btnContent = $this->getLink($label, $href, self::CST_TEXT_WHITE);
            } else {
                $btnClass = self::CST_DISABLED.' '.self::CST_TEXT_WHITE;
                $btnContent = $label;
            }
            $btnAttributes = array(self::ATTR_CLASS=>$btnClass);
            $strPagination .= self::CST_NBSP.$this->getButton($btnContent, $btnAttributes);
            $objs = array_slice($objs, $firstItem, $nbItemsPerPage);
        }
        return $strPagination;
    }
    
    /**
     * @return string
     * @since 2.22.12.07
     * @version 2.22.12.07
     */
    public function getDownloadButton()
    {
        ///////////////////////////////////////////
        // On groupe un bouton de download
        $btnContent = $this->getIcon(self::I_DOWNLOAD);
        $btnAttributes = array(
            self::ATTR_CLASS => 'btn-light ajaxAction',
            self::ATTR_TITLE => self::LABEL_EXPORTER_LISTE,
            self::ATTR_DATA_TRIGGER => 'click',
            self::ATTR_DATA_AJAX => self::CST_CSV_EXPORT,
            'data-filter' => $this->getActiveFilters(),
        );
        if ($this->slugSubOnglet=='') {
            $btnAttributes[self::ATTR_DATA_TYPE] = $this->slugOnglet;
        } else {
            $btnAttributes[self::ATTR_DATA_TYPE] = $this->slugSubOnglet;
        }
        $btnDownload = $this->getButton($btnContent, $btnAttributes);
        
        // Avec un dropdown
        $btnAttributes = array(
            self::ATTR_ID => 'dropdownDownload',
            self::ATTR_CLASS => 'btn-light dropdown-toggle',
            'data-bs-toggle' => 'dropdown',
            'aria-expanded' => 'false',
        );
        $btnDropdown = $this->getButton('Tous', $btnAttributes);
        
        // Les choix possibles
        $ulContent  = $this->getDownloadUls();
        $strStyle  = 'position: absolute; inset: 0px auto auto 0px; margin: 0px; ';
        $strStyle .= 'transform: translate3d(93.6px, 427.2px, 0px);';
        $ulAttributes = array(
            self::ATTR_CLASS => 'dropdown-menu',
            self::ATTR_STYLE => $strStyle,
            'data-popper-placement' => 'bottom-start',
        );
        $ulDropdown = $this->getBalise(self::TAG_UL, $ulContent, $ulAttributes);
        
        $divGroup = $this->getDiv($btnDropdown.$ulDropdown, array('role'=>'group', self::ATTR_CLASS=>'btn-group'));
        
        $attributes = array('role'=>'group', self::ATTR_CLASS=>'btn-group', 'aria-label'=>'Choix export');
        return $this->getDiv($btnDownload.$divGroup, $attributes);
    }
    
    public function getLiDropdown($label, $tag)
    {
        $strClasse  = 'dropdown-item text-white ajaxAction';
        $attributes = array(
            self::ATTR_DATA_TRIGGER => 'click',
            self::ATTR_DATA_AJAX => 'dropdown',
            self::ATTR_DATA_TARGET => '#'.$tag
        );
        return $this->getBalise(self::TAG_LI, $this->getLink($label, '#', $strClasse, $attributes));
    }
    
    public function getActiveFilters()
    {
        $arrActiveFilters = array();
        if ($this->filtreAdherent!='') {
            $arrActiveFilters[] = 'filter-adherent='.$this->filtreAdherent;
        }
        if ($this->filtreDivision!='') {
            $arrActiveFilters[] = 'filter-division='.$this->filtreDivision;
        }
        return implode(',', $arrActiveFilters);
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
        return $ulContent.$this->getLiDropdown('Tous', 'dropdownDownload');
    }
    
    public function getTrFiltres()
    { return ''; }
    
    public function getListContent()
    { return ''; }
    
    public function buildBreadCrumbs()
    {
        $urlElements = array(self::CST_ONGLET=>$this->slugOnglet);
        $buttonContent = $this->getLink($this->titreOnglet, $this->getUrl($urlElements), self::CST_TEXT_WHITE);
        $buttonAttributes = array(self::ATTR_CLASS=>self::CSS_BTN_DARK.' '.self::CSS_DISABLED);
        $this->breadCrumbsContent .= $this->getButton($buttonContent, $buttonAttributes);
    }
    
    /**
     * Retourne le filtre spécifique à la Division.
     * @return string
     * @param v2.22.12.18
     * @since v2.22.12.18
     */
    public function getFiltreDivision($arrFilters)
    {
        /////////////////////////////////////////////
        // Définition de quelques variables
        $urlTemplate = self::WEB_PPF_FILTRE;
        $label = 'Tous';
        $strOptions = '';
        $strClass = 'dropdown-item text-white';
        /////////////////////////////////////////////
        
        /////////////////////////////////////////////
        // Définition de l'url de base pour la redirection
        $baseUrl  = $this->getUrl();
        foreach ($arrFilters as $key => $value) {
            if ($key=='division') {
                continue;
            }
            $baseUrl .= self::CST_AMP.'filter-'.$key.'='.$value;
        }
        $baseUrl .= self::CST_AMP.'filter-division=';
        /////////////////////////////////////////////
        
        /////////////////////////////////////////////
        // Construction des options
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
        /////////////////////////////////////////////
        
        // Définition des attributs pour le template
        $attributes = array(
            $label,
            $strOptions,
        );
        
        return $this->getRender($urlTemplate, $attributes);
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
     * @return string
     * @since v2.22.12.23
     * @version v2.22.12.23
     */
    public function getButtonCreationAnnulation()
    {
        $blnIsEditorPage    = ($this->slugAction==self::CST_WRITE);
        $blnIsDeletePage    = ($this->slugAction==self::CST_DELETE);
        $blnConfirm         = $this->initVar(self::CST_CONFIRM, false);
        
        if ($blnIsEditorPage) {
            // Bouton Annuler
            $strBtnCreationAnnulation = $this->getCancelButton();
        } elseif ($blnIsDeletePage) {
            if ($blnConfirm) {
                // Bouton Retour
                $strBtnCreationAnnulation = $this->getReturnButton();
            } else {
                // Bouton Annuler
                $strBtnCreationAnnulation = $this->getCancelButton();
            }
        } else {
            // Bouton Créer
            $strBtnCreationAnnulation = $this->getCreateButton();
        }
        return $strBtnCreationAnnulation;
    }
    
    /**
     * @since 1.22.12.26
     * @version 1.22.12.26
     */
    public function getAlertContent($type, $msg)
    {
        $strContent  = '<div class="alert alert-'.$type.' d-flex align-items-center" role="alert">';
        $strContent .= '    <i class="fa-solid fa-exclamation-circle mr-2"></i>';
        $strContent .= '    <div>'.$msg.'</div>';
        $strContent .= '</div>';
        return $strContent;
    }
}
