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
 * @version 1.22.12.07
 */
class AdministrationActions extends LocalActions
{
    //////////////////////////////////////////////////
    // CONSTRUCT
    //////////////////////////////////////////////////
    /**
     * @since 1.22.12.07
     * @version 1.22.12.07
     */
    public static function getCsvExport()
    {
        $arrIds = explode(',', $_POST['ids']);
        $arrToExport = array();
        $objAdministrationServices = new AdministrationServices();

        // On initialise un objet et on récupère l'entête
        $objAdministration = new AdministrationClass();
        // On récupère l'entête
        $arrToExport[] = $objAdministration->getCsvEntete();

        // On récupère les données de tous les objets sélectionnés
        foreach ($arrIds as $id) {
            if (empty($id)) {
                continue;
            }
            $objAdministration = $objAdministrationServices->getAdministrationById($id);
            $arrToExport[] = $objAdministration->toCsv();
        }
        
        // On retourne le message de réussite.
        return LocalActions::exportFile($arrToExport, ucfirst(self::ONGLET_ADMINISTRATIFS));
    }
    
}
