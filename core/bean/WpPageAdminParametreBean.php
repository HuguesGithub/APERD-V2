<?php
namespace core\bean;

if (!defined('ABSPATH')) {
    die('Forbidden');
}
/**
 * Classe WpPageAdminParametreBean
 * @author Hugues
 * @since v2.23.01.02
 * @version v2.23.01.02
 */
class WpPageAdminParametreBean extends WpPageAdminBean
{
    public function __construct()
    {
        parent::__construct();
        
        /////////////////////////////////////////
        // Initialisation des variables
        $this->slugOnglet = self::ONGLET_PARAMETRES;
        $this->titreOnglet = self::LABEL_PARAMETRES;
        // Initialisation des données du bloc de présentation
        $this->blnBoutonCreation = false;
        $this->hasPresentation = false;
        $this->strPresentationTitle = self::LABEL_PARAMETRES;
        $this->strPresentationContent = '';
        // Initialisation de la présence d'un bloc import
        $this->hasBlocImport = false;
        // Initialisation d'un éventuel objet dédié.
        // Initialisation de la pagination
        // Initialisation des filtres
        /////////////////////////////////////////
        
        /////////////////////////////////////////
        // Vérification de la soumission d'un formulaire
        if ($this->curUser->hasAdminRights() && $this->postAction!='') {
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
     * @since v2.22.12.21
     * @version v2.22.12.21
     */
    public function dealWithForm()
    {
        /*
        $strNotification = '';
        $strMessage = '';
        
        /////////////////////////////////////////
        // Un formulaire est soumis.
        // On récupère les données qu'on affecte à l'objet
        $this->objAdministratif->setField(self::FIELD_GENRE, $this->initVar(self::FIELD_GENRE));
        $this->objAdministratif->setField(self::FIELD_NOMTITULAIRE, $this->initVar(self::FIELD_NOMTITULAIRE));
        $this->objAdministratif->setField(self::FIELD_LABELPOSTE, $this->initVar(self::FIELD_LABELPOSTE));
        
        // Si le contrôle des données est ok
        if ($this->objAdministratif->controlerDonnees($strNotification, $strMessage)) {
            // Si l'id n'est pas défini
            if ($this->objAdministratif->getField(self::FIELD_ID)=='') {
                // On insère l'objet
                $this->objAdministratif->insert();
                // On renseigne le message d'information.
                $this->strNotifications = $this->getAlertContent(self::NOTIF_SUCCESS, self::MSG_SUCCESS_CREATE);
            } else {
                // On met à jour l'objet
                $this->objAdministratif->update();
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
    
    public function getOngletContent()
    {
        $firstCol  = '1';
        $secondCol = '2';
        $thirdCol  = '3';
        $fourthCol = $this->getCardNavigation();
        
        $urlTemplate = self::WEB_PPFS_CONTENT_CARD_GRID;
        $attributes = array(
            // Identifiant de la page
            $this->slugOnglet,
            // La colonne de gauche
            $firstCol,
            // 
            $secondCol,
            //
            $thirdCol,
            //
            $fourthCol,
        );
        return $this->getRender($urlTemplate, $attributes);
    }
    
    private function getCardNavigation()
    {
        $cardTitle = 'Arborescence du site';
        
        $cardContent  = '<div class="input-group flex-nowrap mb-1"><span class="input-group-text col-2" id="addon-wrapping"><i class="nav-icon fa-solid fa-desktop"></i></span>';
        $cardContent .= '<input type="text" class="form-control" placeholder="Bureau" aria-label="Bureau" value="Bureau"/></div>';

        $cardContent .= '<div class="input-group flex-nowrap mb-1"><span class="input-group-text col-2" id="addon-wrapping"><i class="nav-icon fa-solid fa-users"></i></span>';
        $cardContent .= '<input type="text" class="form-control" placeholder="Administratifs" aria-label="Administratifs" value="Administratifs"/></div>';
        
        $cardContent .= '<div class="input-group flex-nowrap"><span class="input-group-text col-2" id="addon-wrapping"><i class="nav-icon fa-solid fa-school"></i></span>';
        $cardContent .= '<input type="text" class="form-control" placeholder="Divisions" aria-label="Divisions" value="Divisions"/></div>';
        $cardContent .= '<div class="input-group flex-nowrap mb-1"><span class="col-2" id="addon-wrapping">&nbsp;</span>';
        $cardContent .= '<input type="text" class="form-control" placeholder="Composition" aria-label="Composition" value="Composition"/></div>';
        
        $cardContent .= '<div class="input-group flex-nowrap mb-1"><span class="input-group-text col-2" id="addon-wrapping"><i class="nav-icon fa-solid fa-user-graduate"></i></span>';
        $cardContent .= '<input type="text" class="form-control" placeholder="Élèves" aria-label="Élèves" value="Élèves"/></div>';
        
        $cardContent .= '<div class="input-group flex-nowrap mb-1"><span class="input-group-text col-2" id="addon-wrapping"><i class="nav-icon fa-solid fa-users"></i></span>';
        $cardContent .= '<input type="text" class="form-control" placeholder="Enseignants" aria-label="Enseignants" value="Enseignants"/></div>';
        
        $cardContent .= '<div class="input-group flex-nowrap mb-1"><span class="input-group-text col-2" id="addon-wrapping"><i class="nav-icon fa-solid fa-users"></i></span>';
        $cardContent .= '<input type="text" class="form-control" placeholder="Parents" aria-label="Parents" value="Parents"/></div>';
        
        $cardContent .= '<div class="input-group flex-nowrap mb-1"><span class="input-group-text col-2" id="addon-wrapping"><i class="nav-icon fa-solid fa-chalkboard"></i></span>';
        $cardContent .= '<input type="text" class="form-control" placeholder="Matières" aria-label="Matières" value="Matières"/></div>';
        
        $cardContent .= '<div class="input-group flex-nowrap mb-1"><span class="input-group-text col-2" id="addon-wrapping"><i class="nav-icon fa-solid fa-gear"></i></span>';
        $cardContent .= '<input type="text" class="form-control" placeholder="Paramètres" aria-label="Paramètres" value="Paramètres"/></div>';
        
        /*
        $cardContent  = '<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">';
        $cardContent .= '<li class="nav-item"> <input type=text" class="form-control" value=""/></li>';
        $cardContent .= '<li class="nav-item"> <i class="nav-icon fa-solid fa-"></i><p></p></a></li>
        <li class="nav-item"><a href="/admin?onglet=divisions" class="nav-link"><i class="nav-icon fa-solid fa-"></i><p><i class="right fa-solid fa-angle-left"></i></p></a><ul class="nav nav-treeview"><li class="nav-item"><a href="/admin?onglet=divisions&amp;subOnglet=compoDivision" class="nav-link"><i class="nav-icon fa-solid fa-circle"></i><p>Composition</p></a></li></ul></li>
        <li class="nav-item"><a href="/admin?onglet=eleves" class="nav-link"><i class="nav-icon fa-solid fa-"></i><p></p></a></li>
        <li class="nav-item"><a href="/admin?onglet=enseignants" class="nav-link"><i class="nav-icon fa-solid fa-"></i><p><i class="right fa-solid fa-angle-left"></i></p></a><ul class="nav nav-treeview"><li class="nav-item"><a href="/admin?onglet=enseignants&amp;subOnglet=enseignantPrinc" class="nav-link"><i class="nav-icon fa-solid fa-circle"></i><p>Enseignants principaux</p></a></li></ul></li>
        <li class="nav-item"><a href="/admin?onglet=parents" class="nav-link"><i class="nav-icon fa-solid fa-"></i><p><i class="right fa-solid fa-angle-left"></i></p></a><ul class="nav nav-treeview"><li class="nav-item"><a href="/admin?onglet=parents&amp;subOnglet=parentDelegue" class="nav-link"><i class="nav-icon fa-solid fa-circle"></i><p>Parents délégués</p></a></li></ul></li>
        <li class="nav-item"><a href="/admin?onglet=matieres" class="nav-link"><i class="nav-icon fa-solid fa-"></i><p><i class="right fa-solid fa-angle-left"></i></p></a><ul class="nav nav-treeview"><li class="nav-item"><a href="/admin?onglet=matieres&amp;subOnglet=matiereEnseignant" class="nav-link"><i class="nav-icon fa-solid fa-circle"></i><p>Matière par enseignant</p></a></li></ul></li>
        <li class="nav-item"><a href="/admin?onglet=parametres" class="nav-link active"><i class="nav-icon fa-solid fa-"></i><p></p></a></li>';
        $cardContent .= '</ul>';
        */
        
        $urlTemplate = self::WEB_PPFC_CARD;
        $attributes = array(
            $cardTitle,
            $cardContent,
        );
        return $this->getRender($urlTemplate, $attributes);
    }
}
