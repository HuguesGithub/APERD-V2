<?php
namespace core\actions;

use core\domain\AdulteClass;

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
    
    public function __construct()
    {
        parent::__construct();
        $this->obj = new AdulteClass();
        $this->importType = self::ONGLET_PARENTS;
    }
    
    //////////////////////////////////////////////////
    // METHODES
    //////////////////////////////////////////////////
    /**
     * @since 1.22.12.08
     * @version 1.22.12.08
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
            $objsAdulte = $this->objAdulteServices->getAdultesWithFilters();
            foreach ($objsAdulte as $objAdulte) {
                $arrToExport[] = $objAdulte->toCsv();
            }
        } elseif ($ids=='filter') {
            list(, $value) = explode('=', $filters);
            if ($value=='oui') {
                $adh = 1;
            } elseif ($value=='non') {
                $adh = 0;
            } else {
                $adh = '%';
            }

            $arrFilters = array(
                    self::FIELD_ADHERENT => $adh,
            );
            // On récupère toutes les données spécifiques au filtre
            $objsAdulte = $this->objAdulteServices->getAdultesWithFilters($arrFilters);
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
                $objAdulte = $this->objAdulteServices->getAdulteById($id);
                $arrToExport[] = $objAdulte->toCsv();
            }
        }
        
        // On retourne le message de réussite.
        return $this->exportFile($arrToExport, ucfirst(self::ONGLET_PARENTS));
    }
    
}
