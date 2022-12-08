<?php
namespace core\actions;

use core\domain\AdulteClass;
use core\services\AdulteServices;

if (!defined('ABSPATH')) {
    die('Forbidden');
}
/**
 * AdulteActions
 * @author Hugues
 * @since 1.22.12.08
 * @version 1.22.12.08
 */
class AdulteActions extends LocalActions
{
    //////////////////////////////////////////////////
    // CONSTRUCT
    //////////////////////////////////////////////////
    /**
     * @since 1.22.12.08
     * @version 1.22.12.08
     */
    public static function getCsvExport()
    {
        $arrToExport = array();
        $objAdulteServices = new AdulteServices();

        // On initialise un objet et on récupère l'entête
        $objAdulte = new AdulteClass();
        // On récupère l'entête
        $arrToExport[] = $objAdulte->getCsvEntete();
        
        // On vérifie si on veut tous les éléments ou seulement une sélection
        $ids = $_POST[self::CST_IDS];
        if ($ids==self::CST_ALL) {
            // On récupère toutes les données
            $objsAdulte = $objAdulteServices->getAdultesWithFilters();
            foreach ($objsAdulte as $objAdulte) {
                $arrToExport[] = $objAdulte->toCsv();
            }
        } else {
            // On récupère les données de tous les objets sélectionnés
            $arrIds = explode(',', $ids);
            foreach ($arrIds as $id) {
                if (empty($id)) {
                    continue;
                }
                $objAdulte = $objAdulteServices->getAdulteById($id);
                $arrToExport[] = $objAdulte->toCsv();
            }
        }
        
        // On retourne le message de réussite.
        return LocalActions::exportFile($arrToExport, ucfirst(self::ONGLET_PARENTS));
    }
    
}
