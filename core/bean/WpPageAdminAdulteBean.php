<?php
namespace core\bean;

use core\domain\MySQLClass;

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
        // Initialisation des variables
        $this->slugOnglet = self::ONGLET_PARENTS;
        $this->titreOnglet = self::LABEL_PARENTS;
        
        $strNotification = '';
        $strMessage = '';
        
        $id = $this->initVar(self::ATTR_ID);
        $this->objAdulte = $this->objAdulteServices->getAdulteById($id);
        $this->curPage = $this->initVar(self::CST_CURPAGE, 1);
        
        /////////////////////////////////////////
        // Vérification de la soumission d'un formulaire
        $postAction = $this->initVar(self::CST_POST_ACTION);
        if ($postAction!='') {
            // Un formulaire est soumis.
            // On récupère les données qu'on affecte à l'objet
            $this->objAdulte->setField(self::FIELD_NOMADULTE, $this->initVar(self::FIELD_NOMADULTE));
            $this->objAdulte->setField(self::FIELD_PRENOMADULTE, $this->initVar(self::FIELD_PRENOMADULTE));
            $this->objAdulte->setField(self::FIELD_MAILADULTE, $this->initVar(self::FIELD_MAILADULTE));
            $this->objAdulte->setField(self::FIELD_ADHERENT, $this->initVar(self::FIELD_ADHERENT, 0));
            
            // Si le contrôle des données est ok
            if ($this->objAdulte->controlerDonnees($strNotification, $strMessage)) {
                // Si l'id n'est pas défini
                if ($id=='') {
                    // On insère l'objet
                    $this->objAdulte->insert();
                } else {
                    // On met à jour l'objet
                    $this->objAdulte->update();
                }
            } else {
                echo "$strNotification, $strMessage";
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
     * @since 2.22.12.08
     * @version 2.22.12.08
     */
    public function getOngletContent()
    {
        // Définition des droits de l'utilisateur
        $blnHasEditorRights = self::isAdmin();
        $blnIsEditorPage    = ($this->slugSubOnglet==self::CST_WRITE);
        $blnIsDeletePage    = ($this->slugSubOnglet==self::CST_DELETE);
        $blnConfirm         = $this->initVar(self::CST_CONFIRM, false);
        
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
            // Un bloc de présentation
            $this->getRender(self::WEB_PPFC_PRES_ADULTE),
            // Une liste de parents ou un formulaire d'édition.
            $strMainContent,
        );
        return $this->getRender($urlTemplate, $attributes);
    }

    /**
     * @return string
     * @since 2.22.12.08
     * @version 2.22.12.08
     */
    public function getDeleteContent()
    {
        $strElements = '';
        
        // On peut avoir une liste d'id en cas de suppression multiple.
        $ids = $this->initVar(self::ATTR_ID);
        foreach (explode(',', $ids) as $id) {
            $objAdulte = $this->objAdulteServices->getAdulteById($id);
            $strElements .= $this->getBalise(self::TAG_LI, $objAdulte->getName());
        }
        
        $urlElements = array(
            self::ATTR_ID => $ids,
            self::CST_CONFIRM => 1,
        );
        $urlTemplate = self::WEB_PPFC_DEL_ADM;
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
     * @since 2.22.12.08
     * @version 2.22.12.08
     */
    public function getDeletedContent()
    {
        $strElements = '';
        
        // On peut avoir une liste d'id en cas de suppression multiple.
        $ids = $this->initVar(self::ATTR_ID);
        foreach (explode(',', $ids) as $id) {
            $objAdulte = $this->objAdulteServices->getAdulteById($id);
            $strElements .= $this->getBalise(self::TAG_LI, $objAdulte->getName());
            $objAdulte->delete();
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
     * @since 2.22.12.07
     * @version 2.22.12.07
     */
    public function getEditContent()
    {
        $baseUrl = $this->getUrl(array(self::CST_SUBONGLET=>''));
        return $this->objAdulte->getBean()->getForm($baseUrl);
    }
    
    /**
     * @param boolean $blnHasEditorRights
     * @return string
     * @since 2.22.12.07
     * @version 2.22.12.07
     */
    public function getListControlTools($blnHasEditorRights=false)
    {
        $divContent = $this->getRefreshButton().self::CST_NBSP;
        
        // Si on a les droits, on ajoute le bouton de download
        if ($blnHasEditorRights) {
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
     * @param boolean $blnHasEditorRights
     * @return string
     * @since 2.22.12.07
     * @version 2.22.12.07
     */
    public function getListHeaderRow($blnHasEditorRights=false)
    {
        // Selon qu'on a les droit d'administration ou non, on n'aura pas autant de colonnes à afficher.
        $trContent = '';
        if ($blnHasEditorRights) {
            $trContent .= $this->getTh(self::CST_NBSP);
        }
        $trContent .= $this->getTh(self::LABEL_NOMPRENOM);
        $trContent .= $this->getTh(self::LABEL_MAIL);
        $trContent .= $this->getTh(self::LABEL_ADHERENT);
        if ($blnHasEditorRights) {
            $trContent .= $this->getTh(self::LABEL_ACTIONS);
        }
        return $this->getBalise(self::TAG_TR, $trContent);
    }
    
    /**
     * @return string
     * @since 2.22.12.07
     * @version 2.22.12.07
     */
    public function getListContent($blnHasEditorRights=false)
    {
        $strContent = '';
        $this->blnHasPagination = false;
        $this->strPagination = '';
 
        /*
         <button type="button" class="btn btn-default btn-sm disabled text-white"><i class="fa-solid fa-caret-left"></i></button>&nbsp;1 - 10 sur 790&nbsp;
         <button type="button" class="btn btn-default btn-sm "><a href="/admin?onglet=library&amp;subOnglet=index&amp;curPage=2" class="text-white"><i class="fa-solid fa-caret-right"></i></a></button>
         */
        
        /////////////////////////////////////////
        // On va chercher les éléments à afficher
        $objItems = $this->objAdulteServices->getAdultesWithFilters();
        /////////////////////////////////////////////:
        // Pagination
        $this->strPagination = $this->buildPagination($objItems);
        /////////////////////////////////////////////:
        while (!empty($objItems)) {
            $objItem = array_shift($objItems);
            $strContent .= $objItem->getBean()->getRow($blnHasEditorRights);
        }
        /////////////////////////////////////////
        
        $attributes = array(
            // On défini le titre
            self::LABEL_PARENTS,
            // On défini un éventuel entête/footer de boutons d'actions
            $this->getListControlTools($blnHasEditorRights),
            // On défini le tag de la liste
            $this->slugOnglet,
            // On défini la description de la liste
            self::LABEL_LIST_PARENTS,
            // On défini le header de la liste
            $this->getListHeaderRow($blnHasEditorRights),
            // On défini le contenu de la liste
            $strContent,
        );
        return $this->getRender(self::WEB_PPFC_LIST_DEFAULT, $attributes);
    }
    
    /**
     * @return string
     * @since 2.22.12.07
     * @version 2.22.12.07
     */
    public function getDownloadButton()
    {
        $btnContent = $this->getIcon(self::I_DOWNLOAD);
        $btnAttributes = array(
            self::ATTR_CLASS => 'btn btn-default btn-sm btn-light ajaxAction',
            self::ATTR_TITLE => 'Exporter la liste',
            self::ATTR_DATA_TRIGGER => 'click',
            self::ATTR_DATA_AJAX => 'csvExport',
            self::ATTR_DATA_TYPE => $this->slugOnglet,
        );
        return $this->getButton($btnContent, $btnAttributes);
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
}
