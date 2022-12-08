<?php
namespace core\actions;

use core\domain\AdministrationClass;
use core\services\AdministrationServices;

if (!defined('ABSPATH')) {
    die('Forbidden');
}
/**
 * AdministrationActions
 * @author Hugues
 * @since 1.22.12.07
 * @version 1.22.12.08
 */
class AdministrationActions extends LocalActions
{
    //////////////////////////////////////////////////
    // CONSTRUCT
    //////////////////////////////////////////////////
    /**
     * @since 1.22.12.07
     * @version 1.22.12.08
     */
    public static function getCsvExport()
    {
        $arrToExport = array();
        $objAdministrationServices = new AdministrationServices();

        // On initialise un objet et on récupère l'entête
        $objAdministration = new AdministrationClass();
        // On récupère l'entête
        $arrToExport[] = $objAdministration->getCsvEntete();
        
        // On vérifie si on veut tous les éléments ou seulement une sélection
        $ids = $_POST[self::CST_IDS];
        if ($ids==self::CST_ALL) {
            // On récupère toutes les données
            $objsAdministration = $objAdministrationServices->getAdministrationsWithFilters();
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
                $objAdministration = $objAdministrationServices->getAdministrationById($id);
                $arrToExport[] = $objAdministration->toCsv();
            }
        }
        
        // On retourne le message de réussite.
        return LocalActions::exportFile($arrToExport, ucfirst(self::ONGLET_ADMINISTRATIFS));
    }
    
}
