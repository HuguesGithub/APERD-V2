<?php
namespace core\actions;

use core\domain\AdministrationClass;

if (!defined('ABSPATH')) {
    die('Forbidden');
}
/**
 * AdministrationActions
 * @author Hugues
 * @since 1.22.12.07
 * @version 1.22.12.24
 */
class AdministrationActions extends LocalActions
{
    //////////////////////////////////////////////////
    // CONSTRUCT
    //////////////////////////////////////////////////
    
    public function __construct()
    {
        parent::__construct();
        $this->obj = new AdministrationClass();
        $this->importType = self::ONGLET_ADMINISTRATIFS;
    }
    
    //////////////////////////////////////////////////
    // METHODES
    //////////////////////////////////////////////////
    /**
     * @since 1.22.12.07
     * @version 1.22.12.24
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
            $objsAdministration = $this->objAdministrationServices->getAdministrationsWithFilters();
            foreach ($objsAdministration as $objAdministration) {
                $arrToExport[] = $objAdministration->toCsv();
            }
        } else {
            // On récupère les données de tous les objets sélectionnés
            $arrIds = explode(',', $ids);
            foreach ($arrIds as $id) {
                if (empty($id)) {
                    continue;
                }
                $objAdministration = $this->objAdministrationServices->getAdministrationById($id);
                $arrToExport[] = $objAdministration->toCsv();
            }
        }
        
        // On retourne le message de réussite.
        return $this->exportFile($arrToExport, ucfirst(self::ONGLET_ADMINISTRATIFS));
    }
}
