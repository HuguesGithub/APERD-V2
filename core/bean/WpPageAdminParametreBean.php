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
        
       
        $urlTemplate = self::WEB_PPFC_CARD;
        $attributes = array(
            $cardTitle,
            '',
        );
        return $this->getRender($urlTemplate, $attributes);
    }
}
