<?php
namespace core\actions;

use core\domain\EleveClass;

if (!defined('ABSPATH')) {
    die('Forbidden');
}
/**
 * EleveActions
 * @author Hugues
 * @since 1.22.12.23
 * @version 1.22.12.28
 */
class EleveActions extends LocalActions
{
    //////////////////////////////////////////////////
    // CONSTRUCT
    //////////////////////////////////////////////////
    
    public function __construct()
    {
        parent::__construct();
        $this->obj = new EleveClass();
        $this->importType = self::ONGLET_ELEVES;
    }
    
    //////////////////////////////////////////////////
    // METHODES
    //////////////////////////////////////////////////
    /**
     * @since 1.22.12.23
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
            $objsEleve = $this->objEleveServices->getElevesWithFilters();
            foreach ($objsEleve as $objEleve) {
                $arrToExport[] = $objEleve->toCsv();
            }
        } elseif ($ids=='filter') {
            $arrActiveFilters = $this->getActiveFilters($filters);
            
            // On récupère toutes les données spécifiques au filtre
            $objsEleve = $this->objEleveServices->getElevesWithFilters($arrActiveFilters);
            foreach ($objsEleve as $objEleve) {
                $arrToExport[] = $objEleve->toCsv();
            }
        } else {
            // On récupère les données de tous les objets sélectionnés
            $arrIds = explode(',', $ids);
            foreach ($arrIds as $id) {
                if (empty($id)) {
                    continue;
                }
                $objEleve = $this->objEleveServices->getEleveById($id);
                $arrToExport[] = $objEleve->toCsv();
            }
        }
        
        // On retourne le message de réussite.
        return $this->exportFile($arrToExport, ucfirst(self::ONGLET_ELEVES));
    }
}
