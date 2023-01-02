<?php
namespace core\actions;

use core\domain\MatiereEnseignantClass;

if (!defined('ABSPATH')) {
    die('Forbidden');
}
/**
 * MatiereEnseignantActions
 * @author Hugues
 * @since 1.22.12.18
 * @version 1.22.12.28
 */
class MatiereEnseignantActions extends LocalActions
{
    //////////////////////////////////////////////////
    // CONSTRUCT
    //////////////////////////////////////////////////
    
    public function __construct()
    {
        parent::__construct();
        $this->obj = new MatiereEnseignantClass();
        $this->importType = self::CST_MATIERES_ENSEIGNANTS;
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
        if ($ids==self::CST_ALL) {
            // On récupère toutes les données
            $objsMatiereEnseignant = $this->objMatiereEnseignantServices->getMatiereEnseignantsWithFilters();
            foreach ($objsMatiereEnseignant as $objMatiereEnseignant) {
                $arrToExport[] = $objMatiereEnseignant->toCsv();
            }
        } else {
            // On récupère les données de tous les objets sélectionnés
            $arrIds = explode(',', $ids);
            foreach ($arrIds as $id) {
                if (empty($id)) {
                    continue;
                }
                $objMatiereEnseignant = $this->objMatiereEnseignantServices->getMatiereEnseignantById($id);
                $arrToExport[] = $objMatiereEnseignant->toCsv();
            }
        }
        
        // On retourne le message de réussite.
        return $this->exportFile($arrToExport, ucfirst(self::CST_MATIERES_ENSEIGNANTS));
    }
}
