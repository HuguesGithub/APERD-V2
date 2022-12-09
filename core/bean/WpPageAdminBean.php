<?php
namespace core\bean;

if (!defined('ABSPATH')) {
    die('Forbidden');
}
/**
 * Classe WpPageAdminBean
 * @author Hugues
 * @since 2.22.12.05
 * @version 2.22.12.08
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

        if (isset($_POST['mail'])) {
            // TODO : Mettre en place les contrôles
            
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
                // TODO : On va récupérer le parent qui correspond au mail saisi
                // TODO : Initialiser l'utilisateur courant.
                $_SESSION[self::SESSION_APERD_ID] = $_POST['mail'];
            }
        } elseif (isset($_GET['logout'])) {
            // On cherche a priori à se déconnecter
            unset($_SESSION[self::SESSION_APERD_ID]);
        } elseif (isset($_SESSION[self::SESSION_APERD_ID])) {
            // On navigue sur le site en étant identifié.
            // TODO : Initialiser l'utilisateur courant.
        }
		$sqlAttributes = array(self::FIELD_MAILADULTE=>$_SESSION[self::SESSION_APERD_ID]);
		$objsAdulte = $this->objAdulteServices->getAdultesWithFilters($sqlAttributes);
		if (count($objsAdulte)==1) {
			$this->curUser = array_shift($objsAdulte);
		}
        
        // TODO : Récupération des paramètres plus vaste que des initVar multiples ?
        // $this->analyzeUri();
        
        $this->arrSidebarContent = array(
            self::ONGLET_DESK => array(
                self::CST_ICON  => self::I_DESKTOP,
                self::CST_LABEL => self::LABEL_BUREAU,
            ),
            self::ONGLET_ADMINISTRATIFS => array(
                self::CST_ICON  => self::I_USERS,
                self::CST_LABEL => self::LABEL_ADMINISTRATIFS,
            ),
            self::ONGLET_PARENTS => array(
                self::CST_ICON  => self::I_USERS,
                self::CST_LABEL => self::LABEL_PARENTS,
            ),
        );
        // TODO : on garde un exemple d'entrée avec des enfants. Et le merge pour ajouter les menus des personnes
        // identifiées. Si ce différentiel est maintenu
        /*
                self::ONGLET_CALENDAR => array(
                    self::FIELD_ICON   => 'calendar-days',
                    self::FIELD_LABEL  => 'Calendrier',
                    self::CST_CHILDREN => array(
                        self::CST_CAL_MONTH  => 'Calendrier',
                        self::CST_CAL_EVENT  => 'Événements',
                        self::CST_CAL_PARAM  => 'Paramètres',
                    ),
                ),
            $this->arrSidebarContent = array_merge($this->arrSidebarContent, $this->arrSidebarContentNonGuest);
        */
        
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
    
    /**
     * @return string
     * @since 2.22.12.07
     * @version 2.22.12.07
     */
     public function getPageUrl()
     { return '/'.$this->slugPage; }
     
    // TODO : On arrive à s'en passer ? Ou j'initialise individuellement pour le moment ?
    /**
     * @return string
     * @since 1.22.10.18
     * @version 1.22.10.18
     *
    public function analyzeUri()
    {
        $uri = $_SERVER['REQUEST_URI'];
        $pos = strpos($uri, '?');
        if ($pos!==false) {
            $arrParams = explode('&', substr($uri, $pos+1, strlen($uri)));
            if (!empty($arrParams)) {
                foreach ($arrParams as $param) {
                    if (strpos($param, '=')!==false) {
                        list($key, $value) = explode('=', $param);
                        $this->urlParams[$key] = $value;
                    }
                }
            }
            $uri = substr($uri, 0, $pos-1);
        }
        $pos = strpos($uri, '#');
        if ($pos!==false) {
            $this->anchor = substr($uri, $pos+1, strlen($uri));
        }
        if (isset($_POST)) {
            foreach ($_POST as $key => $value) {
                $this->urlParams[$key] = $value;
            }
        }
        return $uri;
    }

    /**
     * @return string
     * @since 2.22.12.05
     * @version 2.22.12.05
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
                case self::ONGLET_PARENTS :
                    $objBean = new WpPageAdminAdulteBean();
                    break;
                case self::ONGLET_ADMINISTRATIFS :
                    $objBean = new WpPageAdminAdministratifBean();
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
        /*
        <!--
      <li class="nav-item d-none d-sm-inline-block"%5$s>
        <a class="nav-link" href="/admin?onglet=inbox"><i class="fa-solid fa-envelope"></i>%4$s</a>
      </li>
      <!-- /.nav-item -- >
      <!-- Notifications Dropdown Menu -- >
      <li class="nav-item"%5$s>
        <a class="nav-link" data-toggle="dropdown" href="#"><i class="fa-solid fa-bell"></i>%2$s</a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          %3$s
          <div class="dropdown-divider"></div>
          <a href="/admin?onglet=inbox&subOnglet=alert" class="dropdown-item dropdown-footer">Toutes les Notifications</a>
        </div>
      </li>
      <!-- /.nav-item -- >
      <li class="nav-item d-none d-sm-inline-block"%5$s>
        <a class="nav-link" href="/admin?onglet=settings"><i class="fa-solid fa-gear"></i></a>
      </li>
      <!-- /.nav-item -- >
      <li class="nav-item"%5$s>
        <a class="nav-link" href="/admin?onglet=profile"><i class="fa-solid fa-user"></i></a>
      </li>
      -->
       <!-- /.nav-item -->
         *
       // Nom Prénom de la personne logguée
     $this->CopsPlayer->getFullName(),
     // Si présence de notifications, le badge
     // <span class="badge badge-warning navbar-badge">0</span>
     '',
     // La liste des notifications
     // Ou un message adapté s'il n'y en a pas.
     '<span class="dropdown-item dropdown-header">Aucune nouvelle Notification</span>',
     // Si présence d'un nouveau mail, le badge
     ($nbMailsNonLus!=0 ? '<span class="badge badge-success navbar-badge">'.$nbMailsNonLus.'</span>' : ''),
     // Si Guest, on cache des trucs.
     ($_SESSION[self::FIELD_MATRICULE]=='Guest' ? ' style="display:none !important;"' : ''),
     );
        */
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
     * @version 2.22.12.07
     */
    public function getSideBar()
    {
        $urlTemplate = self::WEB_PPFN_NAV_SIDEBAR;

        $sidebarContent = '';
        foreach ($this->arrSidebarContent as $strOnglet => $arrOnglet) {
            // Si c'est l'onglet courant ou par défaut
            $blnCurrent = ($this->slugOnglet==$strOnglet || $this->slugOnglet=='' && $strOnglet==self::ONGLET_DESK);
            // Le lien a-t-il des enfants ?
            $hasChildren = isset($arrOnglet[self::CST_CHILDREN]);

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
                self::ATTR_CLASS => 'nav-link'.($blnCurrent ? ' '.self::CST_ACTIVE : ''),
            );
            $superLiContent = $this->getBalise(self::TAG_A, $aContent, $aAttributes);
            
            // TODO : Gestion des enfants
            
            // Construction de l'élément de la liste
            //.($this->slugOnglet ? ' menu-open' : '') ne sert à rien pour le moment
            $liAttributes = array(self::ATTR_CLASS=>'nav-item');
            $sidebarContent .= $this->getBalise(self::TAG_LI, $superLiContent, $liAttributes);
        }
        
        $attributes = array(
            $sidebarContent,
        );
        return $this->getRender($urlTemplate, $attributes);
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
     * @since 2.22.12.07
     * @version 2.22.12.07
     */
    public function getOngletContent()
    { return 'A définir : Bureau'; }
  
    /**
     * @since 1.22.10.18
     * @version 1.22.10.18
     *
    public function getFolderBlock()
    {
        $urlTemplate = 'web/pages/public/fragments/public-fragments-li-menu-folder.php';
        /////////////////////////////////////////
        // Construction du panneau de gauche
        $strLeftPanel = '';
        foreach ($this->arrSubOnglets as $slug => $subOnglet) {
            // On exclu les sous onglets sans icones
            if (!isset($subOnglet[self::FIELD_ICON])) {
                continue;
            }
            // On construit l'entrée de l'onglet/
            $attributes = array(
                // Menu sélectionné ou pas ?
                ($slug==$this->slugSubOnglet ? ' '.self::CST_ACTIVE : ''),
                // L'url du folder
                $this->getSubOngletUrl($slug),
                // L'icône
                $subOnglet[self::FIELD_ICON],
                // Le libellé
                $subOnglet[self::FIELD_LABEL],
            );
            $strLeftPanel .= $this->getRender($urlTemplate, $attributes);
        }
        /////////////////////////////////////////
        return $strLeftPanel;
    }

    /**
     * @param array $urlElements
     * @return string
     * @since 1.22.10.28
     * @version 1.22.10.28
     *
    public function getOngletUrl($urlElements=array())
    {
        $url = $this->getPageUrl().'?'.self::CST_ONGLET.'='.$this->slugOnglet;
        if (isset($urlElements[self::CST_SUBONGLET])) {
            $url .= self::CST_AMP.self::CST_SUBONGLET.'='.$urlElements[self::CST_SUBONGLET];
            unset($urlElements[self::CST_SUBONGLET]);
        }
        if (!empty($urlElements)) {
            foreach ($urlElements as $key => $value) {
                if ($value!='') {
                    $url .= self::CST_AMP.$key.'='.$value;
                }
            }
        }
        return $url;
    }
    
    */
    
    /**
     * @return string
     * @since 2.22.12.07
     * @version 2.22.12.07
     */
    public function getBulkDeleteButton()
    {
        ///////////////////////////////////////////
        // On groupe un bouton de download
        $btnContent = $this->getIcon(self::I_DELETE);
        $btnAttributes = array(
            self::ATTR_CLASS => 'btn-light ajaxAction',
            self::ATTR_TITLE => 'Supprimer la liste',
        );
        $btnBulkDelete = $this->getButton($btnContent, $btnAttributes);

        // Avec un dropdown Sélection / Tous, avec Tous par défaut.
        $btnAttributes = array(
            self::ATTR_ID => 'dropdownTrash',
            self::ATTR_CLASS => 'btn-light dropdown-toggle',
            'data-bs-toggle' => 'dropdown',
            'aria-expanded' => 'false',
        );
        $btnDropdown = $this->getButton('Tous', $btnAttributes);
        
        // Les choix possibles
        $ulContent  = $this->getBalise(self::TAG_LI, $this->getLink('Sélection', '#', 'dropdown-item text-white ajaxAction', array('data-trigger'=>'click', 'data-ajax'=>'dropdown', 'data-target'=>'#dropdownTrash')));
        $ulContent .= $this->getBalise(self::TAG_LI, $this->getLink('Tous', '#', 'dropdown-item text-white ajaxAction', array('data-trigger'=>'click', 'data-ajax'=>'dropdown', 'data-target'=>'#dropdownTrash')));
        $ulAttributes = array(
            self::ATTR_CLASS => 'dropdown-menu',
            self::ATTR_STYLE => 'position: absolute; inset: 0px auto auto 0px; margin: 0px; transform: translate3d(93.6px, 427.2px, 0px);',
            'data-popper-placement' => 'bottom-start',
        );
        $ulDropdown = $this->getBalise(self::TAG_UL, $ulContent, $ulAttributes);
        
        $divGroup = $this->getDiv($btnDropdown.$ulDropdown, array('role'=>'group', self::ATTR_CLASS=>'btn-group'));
        
        return $this->getDiv($btnBulkDelete.$divGroup, array('role'=>'group', self::ATTR_CLASS=>'btn-group', 'aria-label'=>'Choix suppression'));
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
            self::ATTR_DATA_TYPE => $this->slugOnglet,
        );
        $btnDownload = $this->getButton($btnContent, $btnAttributes);

        // Avec un dropdown Sélection / Tous, avec Tous par défaut.
        $btnAttributes = array(
            self::ATTR_ID => 'dropdownSelection',
            self::ATTR_CLASS => 'btn-light dropdown-toggle',
            'data-bs-toggle' => 'dropdown',
            'aria-expanded' => 'false',
        );
        $btnDropdown = $this->getButton('Tous', $btnAttributes);
        
        // Les choix possibles
        $ulContent  = $this->getBalise(self::TAG_LI, $this->getLink('Sélection', '#', 'dropdown-item text-white ajaxAction', array('data-trigger'=>'click', 'data-ajax'=>'dropdown', 'data-target'=>'#dropdownSelection')));
        $ulContent .= $this->getBalise(self::TAG_LI, $this->getLink('Tous', '#', 'dropdown-item text-white ajaxAction', array('data-trigger'=>'click', 'data-ajax'=>'dropdown', 'data-target'=>'#dropdownSelection')));
        $ulAttributes = array(
            self::ATTR_CLASS => 'dropdown-menu',
            self::ATTR_STYLE => 'position: absolute; inset: 0px auto auto 0px; margin: 0px; transform: translate3d(93.6px, 427.2px, 0px);',
            'data-popper-placement' => 'bottom-start',
        );
        $ulDropdown = $this->getBalise(self::TAG_UL, $ulContent, $ulAttributes);
        
        $divGroup = $this->getDiv($btnDropdown.$ulDropdown, array('role'=>'group', self::ATTR_CLASS=>'btn-group'));
        
        return $this->getDiv($btnDownload.$divGroup, array('role'=>'group', self::ATTR_CLASS=>'btn-group', 'aria-label'=>'Choix export'));
    }
    
    public function getCancelButton()
    {
        $label = $this->getIcon(self::I_ANGLES_LEFT).self::CST_NBSP.self::LABEL_ANNULER;
        $href = $this->getUrl(array(self::CST_SUBONGLET=>''));
        return $this->getLinkedButton($label, $href);
    }
    
    public function getReturnButton()
    {
        $label = $this->getIcon(self::I_ANGLES_LEFT).self::CST_NBSP.self::LABEL_RETOUR;
        $href = $this->getUrl(array(self::CST_SUBONGLET=>''));
        return $this->getLinkedButton($label, $href);
    }
    
    public function getCreateButton()
    {
        $label = $this->getIcon(self::I_EDIT).self::CST_NBSP.self::LABEL_CREER_ENTREE;
        $href = $this->getUrl(array(self::CST_SUBONGLET=>self::CST_WRITE));
        return $this->getLinkedButton($label, $href);
    }
    
    public function getRefreshButton()
    {
        $aContent = $this->getIcon(self::I_REFRESH);
        $btnContent = $this->getLink($aContent, $this->getUrl(), 'text-dark');
        return $this->getButton($btnContent, array(self::ATTR_CLASS=>'btn btn-default btn-sm btn-light'));
    }
    
    public function getLinkedButton($label, $href)
    {
        $btnAttributes = array(self::ATTR_CLASS=>'btn btn-primary mb-3 btn-block');
        $strButton = $this->getButton($label, $btnAttributes);
        return $this->getLink($strButton, $href, '');
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
    public function getDefaultListContent($objItems, $blnHasEditorRights=false)
    {
        /////////////////////////////////////////
        // Pagination
        $this->blnHasPagination = false;
        $this->strPagination = $this->buildPagination($objItems);
        /////////////////////////////////////////
        $strContent = '';
        while (!empty($objItems)) {
            $objItem = array_shift($objItems);
            $strContent .= $objItem->getBean()->getRow($blnHasEditorRights);
        }
        /////////////////////////////////////////
        
        $attributes = array(
            // On défini le titre
            $this->titreOnglet,
            // On défini un éventuel entête/footer de boutons d'actions
            $this->getListControlTools($blnHasEditorRights),
            // On défini le tag de la liste
            $this->slugOnglet,
            // On défini la description de la liste
            $this->attrDescribeList,
            // On défini le header de la liste
            $this->getListHeaderRow($blnHasEditorRights),
            // On défini le contenu de la liste
            $strContent,
        );
        return $this->getRender(self::WEB_PPFC_LIST_DEFAULT, $attributes);
    }
    
    /**
     * @return string
     * @since 2.22.12.08
     * @version 2.22.12.08
     */
    public function getCommonOngletContent()
    {
        // Définition des droits de l'utilisateur
        $blnHasEditorRights = self::isAdmin();
        $blnIsEditorPage    = ($this->slugSubOnglet==self::CST_WRITE);
        $blnIsDeletePage    = ($this->slugSubOnglet==self::CST_DELETE);
        $blnConfirm         = $this->initVar(self::CST_CONFIRM, false);
		$strBlocImport = '';
        
        // Définition éventuelle du bouton Création / Annulaiton
        // Définition du contenu de la page.
        $strBtnCreationAnnulation = '';
        $strMainContent = '';
        // Si on a les droits et on est sur une page d'édition
        if ($blnHasEditorRights) {
            if ($blnIsEditorPage) {
                // Bouton Annuler
                $strBtnCreationAnnulation = $this->getCancelButton();
                // Interface d'édition
                $strMainContent = $this->getEditContent();
            } elseif ($blnIsDeletePage) {
                if ($blnConfirm) {
                    // Bouton Retour
                    $strBtnCreationAnnulation = $this->getReturnButton();
                    // Interface de suppression
                    $strMainContent = $this->getDeletedContent();
                } else {
                    // Bouton Annuler
                    $strBtnCreationAnnulation = $this->getCancelButton();
                    // Interface de suppression
                    $strMainContent = $this->getDeleteContent();
                }
            } else {
                // Bouton Créer
                $strBtnCreationAnnulation = $this->getCreateButton();
                // Interface de liste
                $strMainContent = $this->getListContent($blnHasEditorRights);
				
				$url = self::WEB_PPFC_UPLOAD;
				$impAttributes = array($this->slugOnglet);
				$strBlocImport  = $this->getRender($url, $impAttributes);
				$strBlocImport .= $this->getDiv('', array(self::ATTR_ID=>'alertBlock'));
            }
        } else {
            // Interface de liste
            $strMainContent = $this->getListContent($blnHasEditorRights);
        }
        
        $urlTemplate = self::WEB_PPFS_CONTENT_ONE_4TH;
        $attributes = array(
            // Identifiant de la page
            $this->slugOnglet,
            // Un éventuel bouton de Création / Annulation si on a les droits
            $strBtnCreationAnnulation,
            // Un bloc de présentation + un éventuel bloc d'import
            $this->getRender($this->urlOngletContentTemplate).$strBlocImport,
            // Une liste d'administratifs ou un formulaire d'édition.
            $strMainContent,
        );
        return $this->getRender($urlTemplate, $attributes);
    }
    
    /**
     * @param boolean $blnHasEditorRights
     * @return string
     * @since 2.22.12.07
     * @version 2.22.12.08
     */
    public function getListControlTools($blnHasEditorRights=false)
    {
        $divContent = $this->getRefreshButton().self::CST_NBSP;
        
        // Si on a les droits, on ajoute le bouton de download
        if ($blnHasEditorRights) {
			$divContent .= $this->getBulkDeleteButton().self::CST_NBSP;
            $divContent .= $this->getDownloadButton();
        }
        
        // On ajoute le div de pagination, s'il y a lieu
        if ($this->blnHasPagination) {
            $divContent .= $this->getDiv($this->strPagination, array(self::ATTR_CLASS=>'float-right'));
        }
        
        $divAttributes = array(self::ATTR_CLASS=>$this->slugOnglet.'-controls toolbox-controls');
        return $this->getDiv($divContent, $divAttributes);
    }
}
