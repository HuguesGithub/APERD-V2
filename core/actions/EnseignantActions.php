<?php
namespace core\actions;

use core\domain\EnseignantClass;

if (!defined('ABSPATH')) {
    die('Forbidden');
}
/**
 * AdulteActions
 * @author Hugues
 * @since v2.23.01.02
 * @version v2.23.01.02
 */
class EnseignantActions extends LocalActions
{
    //////////////////////////////////////////////////
    // CONSTRUCT
    //////////////////////////////////////////////////
    
    public function __construct()
    {
        parent::__construct();
        $this->obj = new EnseignantClass();
        $this->importType = self::ONGLET_ENSEIGNANTS;
    }
    
    //////////////////////////////////////////////////
    // METHODES
    //////////////////////////////////////////////////
    /**
     * @since v2.23.01.02
     * @version v2.23.01.02
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
            $objsEnseignant = $this->objEnseignantServices->getEnseignantsWithFilters();
            foreach ($objsEnseignant as $objEnseignant) {
                $arrToExport[] = $objEnseignant->toCsv();
            }
        } else {
            // On récupère les données de tous les objets sélectionnés
            $arrIds = explode(',', $ids);
            foreach ($arrIds as $id) {
                if (empty($id)) {
                    continue;
                }
                $objEnseignant = $this->objEnseignantServices->getEnseignantById($id);
                $arrToExport[] = $objEnseignant->toCsv();
            }
        }
        
        // On retourne le message de réussite.
        return $this->exportFile($arrToExport, ucfirst(self::ONGLET_ENSEIGNANTS));
    }
}
