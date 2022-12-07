<?php
namespace core\bean;

if (!defined('ABSPATH')) {
    die('Forbidden');
}
/**
 * Classe WpPageAdminAdministratifBean
 * @author Hugues
 * @since 1.22.09.20
 * @version 1.22.10.19
 */
class WpPageAdminAdministratifBean extends WpPageAdminBean
{
    public function __construct()
    {
        parent::__construct();
        // Initialisation des variables
        $this->slugOnglet = self::ONGLET_ADMINISTRATIFS;
        $this->titreOnglet = self::LABEL_ADMINISTRATIFS;
        
        /*
        $this->slugOnglet = self::ONGLET_ENQUETE;
        
        /////////////////////////////////////////
        // Construction du menu de l'inbox
        $this->arrSubOnglets = array(
            self::CST_FILE_OPENED => array(self::FIELD_ICON => self::I_FILE_OPENED, self::FIELD_LABEL => 'En cours'),
            self::CST_FILE_CLOSED => array(self::FIELD_ICON => self::I_FILE_CLOSED, self::FIELD_LABEL => 'Classées'),
            self::CST_FILE_COLDED => array(self::FIELD_ICON => self::I_FILE_COLDED, self::FIELD_LABEL => 'Cold Case'),
            self::CST_ENQUETE_READ => array(self::FIELD_LABEL => 'Lire'),
            self::CST_ENQUETE_WRITE => array(self::FIELD_LABEL => 'Rédiger'),
        );
        /////////////////////////////////////////

        /////////////////////////////////////////
        // Définition des services
        $this->CopsEnqueteServices = new CopsEnqueteServices();
        */
        
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
     * @since 1.22.10.19
     * @version 1.22.10.19
     *
    public function initBoard()
    {
        /////////////////////////////////////////
        // Création du Breadcrumbs
        $this->slugSubOnglet = $this->initVar(self::CST_SUBONGLET, self::CST_FILE_OPENED);
        $this->buildBreadCrumbs('Enquêtes', self::ONGLET_ENQUETE, true);

        ////////////////////////////////////////////////////////
        // Si formulaire soumis, mise à jour ou insertion.
        if (isset($this->urlParams[self::CST_WRITE_ACTION])) {
            // Insertion / Mise à jour de l'enquête saisie via le formulaire
            // Mais seulement si le nom de l'enquête a été saisi.
            if ($this->urlParams[self::FIELD_NOM_ENQUETE]!='') {
                if ($this->urlParams[self::FIELD_ID]!='') {
                    $this->CopsEnquete = CopsEnqueteActions::updateEnquete($this->urlParams);
                } else {
                    $this->CopsEnquete = CopsEnqueteActions::insertEnquete($this->urlParams);
                }
            }
        } elseif (isset($this->urlParams[self::CST_ACTION])) {
            // Mise à jour du statut (et donc de la dernière date de modification) sur le lien de la liste.
            // On récupère l'enquête associée à l'id.
            $this->CopsEnquete = $this->CopsEnqueteServices->getEnquete($this->urlParams[self::FIELD_ID]);
            // Si elle existe, on effectue le traitement qui va bien.
            $intStatut = $this->CopsEnquete->getField(self::FIELD_STATUT_ENQUETE);
            if ($this->CopsEnquete->getField(self::FIELD_ID)==$this->urlParams[self::FIELD_ID]
                && $intStatut!=self::CST_ENQUETE_CLOSED
                && ($intStatut==self::CST_ENQUETE_OPENED
                    || $intStatut==self::CST_ENQUETE_COLDED
                    && $this->urlParams[self::CST_ACTION]==self::CST_ENQUETE_OPENED)) {
                        // Si l'enquête existe.
                        // Si l'enquête n'est pas déjà transférée au DA.
                        // Si l'enquête est coldcase, elle ne peut pas être transférée au DA

                        // Si tout est bon,
                        // on classe une enquête, on la réouvre ou on la transfère au DA
                        $this->CopsEnquete->setField(self::FIELD_STATUT_ENQUETE, $this->urlParams[self::CST_ACTION]);
                        $this->CopsEnquete->setField(self::FIELD_DLAST, self::getCopsDate('tsnow'));
                        $this->CopsEnqueteServices->updateEnquete($this->CopsEnquete);
            }
        } else {
            // On récupère l'enquête associée à l'id.
            $this->CopsEnquete = $this->CopsEnqueteServices->getEnquete($this->urlParams[self::FIELD_ID]);
        }
        ////////////////////////////////////////////////////////
    }

    /**
     * @return string
     * @since 2.22.12.07
     * @version 2.22.12.07
     */
    public function getOngletContent()
    {
        // Définition des droits de l'utilisateur
        $blnHasEditorRights = self::isAdmin();
        $blnIsEditorPage    = ($this->slugSubOnglet==self::CST_WRITE);
        
        // Définition éventuelle du bouton Création / Annulaiton
        // Définition du contenu de la page.
        $strBtnCreationAnnulation = '';
        $strMainContent = '';
        // Si on a les droits et on est sur une page d'édition
        if ($blnHasEditorRights) {
            if ($blnIsEditorPage) {
                // Bouton Annuler
                $strBtnCreationAnnulation = '';
                // 				<a href="/admin?onglet=library&amp;subOnglet=index&amp;action=write&amp;curPage=1" class="btn btn-primary mb-3 btn-block">Créer une entrée</a>
                // Interface d'édition
                $strMainContent = '';
            } else {
                // Bouton Créer
                $btnAttributes = array(self::ATTR_CLASS=>'btn btn-primary mb-3 btn-block');
                $label = $this->getIcon(self::I_EDIT).self::CST_NBSP.self::LABEL_CREER_ENTREE;
                $strButton = $this->getButton($label, $btnAttributes);
                $href = $this->getUrl(array(self::CST_ACTION=>self::CST_WRITE));
                $strBtnCreationAnnulation = $this->getLink($strButton, $href, '');
                // Interface de liste
                $listContent = $this->getListContent($blnHasEditorRights);
                $attributes = array(
                    // On défini le titre
                    self::LABEL_ADMINISTRATIFS,
                    // On défini un éventuel entête/footer de boutons d'actions
                    $this->getListControlTools($blnHasEditorRights),
                    // On défini le tag de la liste
                    $this->slugOnglet,
                    // On défini la description de la liste
                    self::LABEL_LIST_ADMINISTRATIFS,
                    // On défini le header de la liste
                    $this->getListHeaderRow($blnHasEditorRights),
                    // On défini le contenu de la liste
                    $listContent,
                    
                );
                $strMainContent = $this->getRender(self::WEB_PPFC_LIST_DEFAULT, $attributes);
            }
        } else {
            // Interface de liste
            $listContent = $this->getListContent($blnHasEditorRights);
            $attributes = array(
                // On défini le titre
                self::LABEL_ADMINISTRATIFS,
                // On défini un éventuel entête/footer de boutons d'actions
                $this->getListControlTools($blnHasEditorRights),
                // On défini le tag de la liste
                $this->slugOnglet,
                // On défini la description de la liste
                self::LABEL_LIST_ADMINISTRATIFS,
                // On défini le header de la liste
                $this->getListHeaderRow($blnHasEditorRights),
                // On défini le contenu de la liste
                $listContent,
                
            );
            $strMainContent = $this->getRender(self::WEB_PPFC_LIST_DEFAULT, $attributes);
        }
        
        $urlTemplate = 'web/pages/publique/fragments/section/section-onglet-content-one-fourth.tpl';
        $attributes = array(
            // Identifiant de la page
            $this->slugOnglet,
            // Un éventuel bouton de Création / Annulation si on a les droits
            $strBtnCreationAnnulation,
            // Un bloc de présentation
            $this->getRender(self::WEB_PPFC_PRES_ADM),
            // Une liste d'administratifs ou un formulaire d'édition.
            $strMainContent,
        );
        return $this->getRender($urlTemplate, $attributes);

        /////////////////////////////////////////
        // Construction du panneau de droite
        $strBtnClass = 'btn btn-primary btn-block mb-3';
        if ($this->slugSubOnglet==self::CST_ENQUETE_READ ||
            $this->CopsEnquete->getField(self::FIELD_ID)!='' &&
            $this->CopsEnquete->getField(self::FIELD_STATUT_ENQUETE)!=self::CST_ENQUETE_OPENED) {
                $strRightPanel   = $this->CopsEnquete->getBean()->getReadEnqueteBlock();
                $attributes = array (
                    self::ATTR_HREF  => $this->getOngletUrl(),
                    self::ATTR_CLASS => $strBtnClass,
                );
                $strContent = $this->getIcon(self::I_BACKWARD).' Retour';
            } elseif ($this->slugSubOnglet==self::CST_ENQUETE_WRITE) {
                $strRightPanel   = $this->CopsEnquete->getBean()->getWriteEnqueteBlock();
                $attributes = array (
                    self::ATTR_HREF  => $this->getOngletUrl(),
                    self::ATTR_CLASS => $strBtnClass,
                );
                $strContent = $this->getIcon(self::I_BACKWARD).' Retour';
            } else {
                $strRightPanel   = $this->getFolderEnquetesList();
                $attributes = array (
                    self::ATTR_HREF  => $this->getSubOngletUrl(self::CST_FOLDER_WRITE),
                    self::ATTR_CLASS => $strBtnClass,
                );
                $strContent = 'Ouvrir une enquête';
            }
        /////////////////////////////////////////

        $attributes = array(
            // Contenu du panneau latéral gauche
            $this->getFolderBlock(),
            // Contenu du panneau principal
            $strRightPanel,
            // Eventuel bouton de retour si on est en train de lire ou rédiger un message
            $this->getBalise(self::TAG_A, $strContent, $attributes),
        );
    }

    /**
     * @since 1.22.09.20
     * @version 1.22.10.19
     *
    public function getFolderEnquetesList()
    {
        $urlTemplate = 'web/pages/public/fragments/public-fragments-section-enquetes-list.php';
        /////////////////////////////////////////
        // Construction du panneau de droite
        // Liste des dossiers pour une catégorie spécifique (En cours, Classées, Cold Case...)
        switch ($this->slugSubOnglet) {
            case self::CST_FILE_CLOSED :
                $attributes = array(self::SQL_WHERE_FILTERS => array(
                    self::FIELD_STATUT_ENQUETE => self::CST_ENQUETE_CLOSED,
                ));
            break;
            case self::CST_FILE_COLDED :
                $attributes = array(self::SQL_WHERE_FILTERS => array(
                    self::FIELD_STATUT_ENQUETE => self::CST_ENQUETE_COLDED,
                ));
             break;
            case self::CST_FILE_OPENED :
            default :
                $attributes = array(self::SQL_WHERE_FILTERS => array(
                    self::FIELD_STATUT_ENQUETE => self::CST_ENQUETE_OPENED,
                ));
            break;
        }

        $objsCopsEnquete = $this->CopsEnqueteServices->getEnquetes($attributes);
        if (empty($objsCopsEnquete)) {
            $strContent = '<tr><td class="text-center">Aucune enquête.<br></td></tr>';
        } else {
            $strContent = '';
            foreach ($objsCopsEnquete as $objCopsEnquete) {
                $strContent .= $objCopsEnquete->getBean()->getCopsEnqueteRow();
            }
        }
        /////////////////////////////////////////
        // Gestion de la pagination
        $strPagination = '';
        /////////////////////////////////////////

        $attributes = array(
            // Titre du dossier affiché
            $this->arrSubOnglets[$this->slugSubOnglet][self::FIELD_LABEL],
            // Nombre de messages dans le dossier affiché : 1-50/200
            $strPagination,
            // La liste des messages du dossier affiché
            $strContent,
            // Le slug du dossier affiché
            $this->getSubOngletUrl(),
        );
        /////////////////////////////////////////
        return $this->getRender($urlTemplate, $attributes);
    }
    */
    
    /**
     * @param boolean $blnHasEditorRights
     * @return string
     * @since 2.22.12.07
     * @version 2.22.12.07
     */
    public function getListControlTools($blnHasEditorRights=false)
    {
        $divContent = '';
        // On ajoute le bouton de refresh ?        
        // <button type="button" class="btn btn-default btn-sm" title="Rafraîchir la liste"><a href="/admin?onglet=library&amp;subOnglet=index&amp;curPage=1" class="text-white"><i class="fa-solid fa-arrows-rotate"></i></a></button>&nbsp;
        
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
        // On veut afficher les éléments suivants (* si les droits d'édition) :
        // * une case à cocher
        // - un nom
        // - un poste
        // * des actions à effectuer
        $trContent = '';
        if ($blnHasEditorRights) {
            $trContent .= $this->getTh(self::CST_NBSP);
        }
        $trContent .= $this->getTh(self::LABEL_NOMTITULAIRE);
        $trContent .= $this->getTh(self::LABEL_LABELPOSTE);
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
        
        $objItems = $this->objAdministrationServices->getAdministrationsWithFilters();
        while (!empty($objItems)) {
            $objItem = array_shift($objItems);
            $strContent .= $objItem->getBean()->getRow($blnHasEditorRights);
        }
        
        return $strContent;
    }
    
    /**
     * @return string
     * @since 2.22.12.07
     * @version 2.22.12.07
     */
    public function getDownloadButton()
    {
        return '<button type="button" class="btn btn-default btn-sm btn-light ajaxAction" title="Exporter la liste" data-trigger="click" data-ajax="csvExport" data-type="'.$this->slugOnglet.'"><i class="fa-solid fa-download"></i></button>';
    }
}
