<?php
namespace core\bean;

if (!defined('ABSPATH')) {
    die('Forbidden');
}
/**
 * Classe WpPageAdminMatiereBean
 * @author Hugues
 * @since 1.22.12.21
 * @version 1.22.12.21
 */
class WpPageAdminMatiereBean extends WpPageAdminBean
{
    public function __construct()
    {
        parent::__construct();
        
        /////////////////////////////////////////
        // Initialisation des variables
        $this->slugOnglet = self::ONGLET_MATIERES;
        $this->titreOnglet = self::LABEL_MATIERE;
        // Initialisation des données du bloc de présentation
        $this->hasPresentation = true;
        $this->strPresentationTitle = self::LABEL_MATIERE;
        $this->strPresentationContent = self::LABEL_INTERFACE_MATIERES_PRES;
        // Initialisation de la présence d'un bloc import
        $this->hasBlocImport = true;
        // Initialisation d'un éventuel objet dédié.
        $id = $this->initVar(self::ATTR_ID);
        $this->objMatiere = $this->objMatiereServices->getMatiereById($id);
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
        
        // Un formulaire est soumis.
        // On récupère les données qu'on affecte à l'objet
        $this->objMatiere->setField(self::FIELD_LABELMATIERE, $this->initVar(self::FIELD_LABELMATIERE));
        // Si le contrôle des données est ok
        if ($this->objMatiere->controlerDonnees($strNotification, $strMessage)) {
            // Si l'id n'est pas défini
            if ($this->objMatiere->getField(self::FIELD_ID)=='') {
                // On insère l'objet
                $this->objMatiere->insert();
            } else {
                // On met à jour l'objet
                $this->objMatiere->update();
            }
        } else {
            // TODO : Le contrôle de données n'est pas bon. Afficher l'erreur.
        }
        // TODO : de manière générale, ce serait bien d'afficher le résultat de l'opération.
    }
    
    /**
     * Construction d'une liste d'éléments dont les identifiants sont passés en paramètre.
     * Si $blnDelete est à true, on en profite pour effacer l'élément.
     * @param int|string $ids
     * @param boolean $blnDelete
     * @return string
     * @since v2.22.12.21
     * @version v2.22.12.21
     */
    public function getListElements($ids, $blnDelete=false)
    {
        $strElements = '';
        // On peut avoir une liste d'id en cas de suppression multiple.
        foreach (explode(',', $ids) as $id) {
            $objMatiere = $this->objMatiereServices->getMatiereById($id);
            $strElements .= $this->getBalise(self::TAG_LI, $objMatiere->getField(self::FIELD_LABELMATIERE));
            if ($blnDelete) {
                $objMatiere->delete();
            }
        }
        return $strElements;
    }
    
    /**
     * @return string
     * @since 2.22.12.21
     * @version 2.22.12.21
     */
    public function getSpecificHeaderRow()
    { return $this->getTh(self::LABEL_LABELMATIERE); }
    
    /**
     * @return string
     * @since 2.22.12.21
     * @version 2.22.12.21
     */
    public function getListContent()
    {
        $this->attrDescribeList = self::LABEL_LIST_MATIERES;
        /////////////////////////////////////////
        // On va chercher les éléments à afficher
        $objItems = $this->objMatiereServices->getMatieresWithFilters();
        /////////////////////////////////////////
        return $this->getDefaultListContent($objItems);
    }
}
