<?php
namespace core\bean;

use core\domain\MySQLClass;

if (!defined('ABSPATH')) {
    die('Forbidden');
}
/**
 * Classe WpPageAdminMatiereEnseignantBean
 * @author Hugues
 * @since v2.23.01.02
 * @version v2.23.01.02
 */
class WpPageAdminMatiereEnseignantBean extends WpPageAdminMatiereBean
{
    public function __construct()
    {
        parent::__construct();

        /////////////////////////////////////////
        // Initialisation des variables
        $this->slugOnglet = self::ONGLET_MATIERES;
        $this->slugSubOnglet = self::CST_MATIERES_ENSEIGNANTS;
        $this->titreOnglet = self::LABEL_MATIERES_ENSEIGNANTS;
        // Initialisation des données du bloc de présentation
        // Initialisation de la présence d'un bloc import
        $this->hasBlocImport = true;
        // Initialisation d'un éventuel objet dédié.
        $id = $this->initVar(self::ATTR_ID);
        $this->objMatiereEnseignant = $this->objMatiereEnseignantServices->getMatiereEnseignantById($id);
        // Initialisation de la pagination
        $this->curPage = $this->initVar(self::CST_CURPAGE, 1);
        // Initialisation des filtres
        // TODO : Filtre par matière ?
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
     * @since v2.22.12.28
     * @version v2.22.12.28
     */
    public function dealWithForm()
    {
        if (!isset($this->objMatiereEnseignant)) {
            return;
        }
        
        $strNotification = '';
        $strMessage = '';
        
        /////////////////////////////////////////
        // Un formulaire est soumis.
        // On récupère les données qu'on affecte à l'objet
        $this->objMatiereEnseignant->setField(self::FIELD_MATIEREID, $this->initVar(self::FIELD_MATIEREID));
        $this->objMatiereEnseignant->setField(self::FIELD_ENSEIGNANTID, $this->initVar(self::FIELD_ENSEIGNANTID));
        
        // Si le contrôle des données est ok
        if ($this->objMatiereEnseignant->controlerDonnees($strNotification, $strMessage)) {
            // Si l'id n'est pas défini
            if ($this->objMatiereEnseignant->getField(self::FIELD_ID)=='') {
                // On insère l'objet
                $this->objMatiereEnseignant->insert();
                // On renseigne le message d'information.
                $this->strNotifications = $this->getAlertContent(self::NOTIF_SUCCESS, self::MSG_SUCCESS_CREATE);
            } else {
                // On met à jour l'objet
                $this->objMatiereEnseignant->update();
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
     * @since v2.23.01.02
     * @version v2.23.01.02
     */
    public function getListElements($ids, $blnDelete=false)
    {
        $strElements = '';
        // On peut avoir une liste d'id en cas de suppression multiple.
        foreach (explode(',', $ids) as $id) {
            $objMatiereEnseignant = $this->objMatiereEnseignantServices->getMatiereEnseignantById($id);
            $strElements .= $this->getBalise(self::TAG_LI, $objMatiereEnseignant->getLibelle());
            if ($blnDelete) {
                $objMatiereEnseignant->delete();
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
        $trContent  = $this->getTh(self::LABEL_MATIERES);
        return $trContent.$this->getTh(self::LABEL_ENSEIGNANTS);
    }
    
    /**
     * @return string
     * @since v2.23.01.02
     * @version v2.23.01.02
     */
    public function getListContent()
    {
        $this->attrDescribeList = self::LABEL_LIST_MATIERES_ENSEIGNANT;
        /////////////////////////////////////////
        // On va prendre en compte les éventuels filtres
        $attributes = array();
        // Sens d'affichage
        $sensTri = self::FIELD_LABELMATIERE;
        /////////////////////////////////////////
        // On va chercher les éléments à afficher
        $objItems = $this->objMatiereEnseignantServices->getMatiereEnseignantsWithFilters($attributes, $sensTri);
        /////////////////////////////////////////
        return $this->getDefaultListContent($objItems);
    }
    
    /**
     * Retourne le filtre spécifique à l'écran.
     * @return string
     * @since v2.22.12.18
     * @param v2.22.12.18
     */
    public function getTrFiltres()
    {
        /*
        /////////////////////////////////////////
        // Filtre en place
        $arrFilters = array(
            'division' => $this->filtreDivision,
            'adherent' => $this->filtreAdherent,
        );
        
        /////////////////////////////////////////
        // On va mettre en place la ligne de Filtre
        $trContent = '';
        if ($this->curUser->hasEditorRights()) {
            $trContent .= $this->getTh(self::CST_NBSP);
        }
        $trContent .= $this->getTh(self::CST_NBSP);
        $trContent .= $this->getFiltreDivision($arrFilters);
        $trContent .= $this->getTh(self::CST_NBSP);
        
        // Filtre Adhérent
        $trContent .= $this->getFiltreAdherent($arrFilters);
        
        if ($this->curUser->hasEditorRights()) {
            $trContent .= $this->getButtonFiltre();
        }
        return $this->getBalise(self::TAG_TR, $trContent);
        */
    }
    
    /**
     * @return string
     * @since v2.23.01.02
     * @version v2.23.01.02
     */
    public function getEditContent()
    {
        $baseUrl = $this->getUrl(array(self::CST_SUBONGLET=>''));
        return $this->objMatiereEnseignant->getBean()->getForm($baseUrl, $this->strNotifications);
    }
}
