<?php
namespace core\bean;

if (!defined('ABSPATH')) {
    die('Forbidden');
}
/**
 * Classe WpPageAdminDivisionBean
 * @author Hugues
 * @since 1.22.12.11
 * @version 1.22.12.11
 */
class WpPageAdminDivisionBean extends WpPageAdminBean
{
    public function __construct()
    {
        parent::__construct();
        
        /////////////////////////////////////////
        // Initialisation des variables
        $this->slugOnglet = self::ONGLET_DIVISIONS;
        $this->titreOnglet = self::LABEL_DIVISIONS;
        // Initialisation des données du bloc de présentation
        $this->hasPresentation = true;
        $this->strPresentationTitle = self::LABEL_DIVISIONS;
        $this->strPresentationContent = self::LABEL_INTERFACE_DIVISIONS_PRES;
        // Initialisation de la présence d'un bloc import
        $this->hasBlocImport = true;
        // Initialisation d'un éventuel objet dédié.
        $id = $this->initVar(self::ATTR_ID);
        $this->objDivision = $this->objDivisionServices->getDivisionById($id);
        // Initialisation de la pagination
        $this->curPage = $this->initVar(self::CST_CURPAGE, 1);
        // Initialisation des filtres
        // Initialisation de la variable de formulaire
        $postAction = $this->initVar(self::CST_POST_ACTION);
        /////////////////////////////////////////
        
        $strNotification = '';
        $strMessage = '';
        
        /////////////////////////////////////////
        // Vérification de la soumission d'un formulaire
        if ($this->curUser->hasEditorRights() && $postAction!='') {
            // Un formulaire est soumis.
            // On récupère les données qu'on affecte à l'objet
            $this->objDivision->setField(self::FIELD_LABELDIVISION, $this->initVar(self::FIELD_LABELDIVISION));
            // Si le contrôle des données est ok
            if ($this->objDivision->controlerDonnees($strNotification, $strMessage)) {
                // Si l'id n'est pas défini
                if ($id=='') {
                    // On insère l'objet
                    $this->objDivision->insert();
                } else {
                    // On met à jour l'objet
                    $this->objDivision->update();
                }
            } else {
                // TODO : Le contrôle de données n'est pas bon. Afficher l'erreur.
            }
            // TODO : de manière générale, ce serait bien d'afficher le résultat de l'opération.
        }
        /////////////////////////////////////////
        
        /////////////////////////////////////////
        // Construction du Breadcrumbs
        $this->buildBreadCrumbs();
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
            $objDivision = $this->objDivisionServices->getDivisionById($id);
            $strElements .= $this->getBalise(self::TAG_LI, $objDivision->getField(self::FIELD_LABELDIVISION));
            if ($blnDelete) {
                $objDivision->delete();
            }
        }
        return $strElements;
    }
    
    /**
     * @return string
     * @since 2.22.12.11
     * @version 2.22.12.18
     */
    public function getSpecificHeaderRow()
    { return $this->getTh(self::LABEL_LABELDIVISION); }
    
    /**
     * @return string
     * @since 2.22.12.11
     * @version 2.22.12.11
     */
    public function getListContent()
    {
        $this->attrDescribeList = self::LABEL_LIST_DIVISIONS;
        /////////////////////////////////////////
        // On va chercher les éléments à afficher
        $objItems = $this->objDivisionServices->getDivisionsWithFilters();
        /////////////////////////////////////////
        return $this->getDefaultListContent($objItems);
    }
}
