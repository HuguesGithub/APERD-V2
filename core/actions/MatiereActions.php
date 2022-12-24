<?php
namespace core\actions;

use core\domain\MatiereClass;

if (!defined('ABSPATH')) {
    die('Forbidden');
}
/**
 * DivisionActions
 * @author Hugues
 * @since 1.22.12.21
 * @version 1.22.12.21
 */
class MatiereActions extends LocalActions
{
    //////////////////////////////////////////////////
    // CONSTRUCT
    //////////////////////////////////////////////////
    
    public function __construct()
    {
        parent::__construct();
        $this->obj = new MatiereClass();
        $this->importType = self::ONGLET_MATIERES;
    }
    
    //////////////////////////////////////////////////
    // METHODES
    //////////////////////////////////////////////////
    /**
     * @since 1.22.12.21
     * @version 1.22.12.21
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
            $objsMatiere = $this->objMatiereServices->getMatieresWithFilters();
            foreach ($objsMatiere as $objMatiere) {
                $arrToExport[] = $objMatiere->toCsv();
            }
        } else {
            // On récupère les données de tous les objets sélectionnés
            $arrIds = explode(',', $ids);
            foreach ($arrIds as $id) {
                if (empty($id)) {
                    continue;
                }
                $objMatiere = $this->objMatiereServices->getMatiereById($id);
                $arrToExport[] = $objMatiere->toCsv();
            }
        }
        
        // On retourne le message de réussite.
        return $this->exportFile($arrToExport, ucfirst(self::ONGLET_MATIERES));
    }
}
