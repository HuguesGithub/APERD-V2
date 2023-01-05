<?php
namespace core\actions;

use core\domain\DivisionCompoClass;

if (!defined('ABSPATH')) {
    die('Forbidden');
}
/**
 * DivisionCompoActions
 * @author Hugues
 * @since v2.23.01.05
 * @version v2.23.01.05
 */
class DivisionCompoActions extends LocalActions
{
    //////////////////////////////////////////////////
    // CONSTRUCT
    //////////////////////////////////////////////////
    
    public function __construct()
    {
        parent::__construct();
        $this->obj = new DivisionCompoClass();
        $this->importType = self::CST_COMPOSITION_DIVISIONS;
    }
    
    //////////////////////////////////////////////////
    // METHODES
    //////////////////////////////////////////////////
    /**
     * @since v2.23.01.05
     * @version v2.23.01.05
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
            $objsDivisionCompo = $this->objDivisionCompoServices->getDivisionComposWithFilters();
            foreach ($objsDivisionCompo as $objDivisionCompo) {
                $arrToExport[] = $objDivisionCompo->toCsv();
            }
        } else {
            // On récupère les données de tous les objets sélectionnés
            $arrIds = explode(',', $ids);
            foreach ($arrIds as $id) {
                if (empty($id)) {
                    continue;
                }
                $objDivisionCompo = $this->objDivisionCompoServices->getDivisionCompoById($id);
                $arrToExport[] = $objDivisionCompo->toCsv();
            }
        }
        
        // On retourne le message de réussite.
        return $this->exportFile($arrToExport, ucfirst(self::CST_COMPOSITION_DIVISIONS));
    }
}
