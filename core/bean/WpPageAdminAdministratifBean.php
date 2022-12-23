<?php
namespace core\bean;

if (!defined('ABSPATH')) {
    die('Forbidden');
}
/**
 * Classe WpPageAdminAdministratifBean
 * @author Hugues
 * @since 1.22.12.01
 * @version 1.22.12.08
 */
class WpPageAdminAdministratifBean extends WpPageAdminBean
{
    public function __construct()
    {
        parent::__construct();
        
        /////////////////////////////////////////
        // Initialisation des variables
        $this->slugOnglet = self::ONGLET_ADMINISTRATIFS;
        $this->titreOnglet = self::LABEL_ADMINISTRATIFS;
        // Initialisation des données du bloc de présentation
        $this->blnBoutonCreation = true;
        $this->hasPresentation = true;
        $this->strPresentationTitle = self::LABEL_ADMINISTRATIFS;
        $this->strPresentationContent = self::LABEL_INTERFACE_ADMINISTRATIFS_PRES;
        // Initialisation de la présence d'un bloc import
        $this->hasBlocImport = true;
        // Initialisation d'un éventuel objet dédié.
        $id = $this->initVar(self::ATTR_ID);
        $this->objAdministratif = $this->objAdministrationServices->getAdministrationById($id);
        // Initialisation de la pagination
        $this->curPage = $this->initVar(self::CST_CURPAGE, 1);
        // Initialisation des filtres
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
            } else {
                // On met à jour l'objet
                $this->objAdministratif->update();
            }
        } else {
            // TODO : Le contrôle de données n'est pas bon. Afficher l'erreur.
        }
        // TODO : de manière générale, ce serait bien d'afficher le résultat de l'opération.
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
            $objAdministratif = $this->objAdministrationServices->getAdministrationById($id);
            $strElements .= $this->getBalise(self::TAG_LI, $objAdministratif->getFullInfo());
            if ($blnDelete) {
                $objAdministratif->delete();
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
        $trContent  = $this->getTh(self::LABEL_NOMTITULAIRE);
        return $trContent.$this->getTh(self::LABEL_LABELPOSTE);
    }
    
    /**
     * @return string
     * @since 2.22.12.07
     * @version 2.22.12.07
     */
    public function getListContent()
    {
        $this->attrDescribeList = self::LABEL_LIST_ADMINISTRATIFS;
        /////////////////////////////////////////
        // On va chercher les éléments à afficher
        $objItems = $this->objAdministrationServices->getAdministrationsWithFilters();
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
        return $this->objAdministratif->getBean()->getForm($baseUrl);
    }
}
