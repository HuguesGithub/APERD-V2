<?php
namespace core\actions;

use core\domain\DivisionClass;

if (!defined('ABSPATH')) {
    die('Forbidden');
}
/**
 * DivisionActions
 * @author Hugues
 * @since 1.22.12.11
 * @version 1.22.12.11
 */
class DivisionActions extends LocalActions
{
    //////////////////////////////////////////////////
    // CONSTRUCT
    //////////////////////////////////////////////////
    
    public function __construct()
    {
        parent::__construct();
        $this->obj = new DivisionClass();
        $this->importType = self::ONGLET_DIVISIONS;
    }
    
    //////////////////////////////////////////////////
    // METHODES
    //////////////////////////////////////////////////
    /**
     * @since 1.22.12.11
     * @version 1.22.12.11
     */
    public function getCsvExport()
    {
        $arrToExport = array();
        // On récupère l'entête
        $arrToExport[] = $this->obj->getCsvEntete();
        
        // On vérifie si on veut tous les éléments ou seulement une sélection
        $ids = $_POST[self::CST_IDS];
        if ($ids==self::CST_ALL) {
            // On récupère toutes les données
            $objsDivision = $this->objDivisionServices->getDivisionsWithFilters();
            foreach ($objsDivision as $objDivision) {
                $arrToExport[] = $objDivision->toCsv();
            }
        } else {
            // On récupère les données de tous les objets sélectionnés
            $arrIds = explode(',', $ids);
            foreach ($arrIds as $id) {
                if (empty($id)) {
                    continue;
                }
                $objDivision = $this->objDivisionServices->getDivisionById($id);
                $arrToExport[] = $objDivision->toCsv();
            }
        }
        
        // On retourne le message de réussite.
        return $this->exportFile($arrToExport, ucfirst(self::ONGLET_DIVISIONS));
    }
}
