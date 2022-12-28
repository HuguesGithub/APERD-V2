<?php
namespace core\actions;

use core\domain\AdulteDivisionClass;

if (!defined('ABSPATH')) {
    die('Forbidden');
}
/**
 * AdulteDivisionActions
 * @author Hugues
 * @since 1.22.12.18
 * @version 1.22.12.28
 */
class AdulteDivisionActions extends LocalActions
{
    //////////////////////////////////////////////////
    // CONSTRUCT
    //////////////////////////////////////////////////
    
    public function __construct()
    {
        parent::__construct();
        $this->obj = new AdulteDivisionClass();
        $this->importType = self::SUBONGLET_PARENTS_DELEGUES;
    }
    
    //////////////////////////////////////////////////
    // METHODES
    //////////////////////////////////////////////////
    /**
     * @since 1.22.12.18
     * @version 1.22.12.28
     */
    public function getCsvExport()
    {
        $arrToExport = array();
        // On récupère l'entête
        $arrToExport[] = $this->obj->getCsvEntete();

        // On vérifie si on veut tous les éléments ou seulement une sélection
        $ids = $_POST[self::CST_IDS];
        $filters = $_POST['filter'];
        if ($ids==self::CST_ALL) {
            // On récupère toutes les données
            $objsAdulteDivision = $this->objAdulteDivisionServices->getAdulteDivisionsWithFilters();
            foreach ($objsAdulteDivision as $objAdulteDivision) {
                $arrToExport[] = $objAdulteDivision->toCsv();
            }
        } elseif ($ids=='filter') {
            $arrActiveFilters = $this->getActiveFilters($filters);
            
            // On récupère toutes les données spécifiques au filtre
            $objsAdulteDivision = $this->objAdulteDivisionServices->getAdulteDivisionsWithFilters($arrActiveFilters);
            foreach ($objsAdulteDivision as $objAdulteDivision) {
                $arrToExport[] = $objAdulteDivision->toCsv();
            }
        } else {
            // On récupère les données de tous les objets sélectionnés
            $arrIds = explode(',', $ids);
            foreach ($arrIds as $id) {
                if (empty($id)) {
                    continue;
                }
                $objAdulteDivision = $this->objAdulteDivisionServices->getAdulteDivisionById($id);
                $arrToExport[] = $objAdulteDivision->toCsv();
            }
        }
        
        // On retourne le message de réussite.
        return $this->exportFile($arrToExport, ucfirst(self::SUBONGLET_PARENTS_DELEGUES));
    }
}
