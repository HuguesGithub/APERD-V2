<?php
namespace core\actions;

use core\domain\EnseignantPrincipalClass;

if (!defined('ABSPATH')) {
    die('Forbidden');
}
/**
 * EnseignantPrincipalActions
 * @author Hugues
 * @since v2.23.01.05
 * @version v2.23.01.05
 */
class EnseignantPrincipalActions extends LocalActions
{
    //////////////////////////////////////////////////
    // CONSTRUCT
    //////////////////////////////////////////////////
    
    public function __construct()
    {
        parent::__construct();
        $this->obj = new EnseignantPrincipalClass();
        $this->importType = self::CST_ENSEIGNANTS_PRINCIPAUX;
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
            $objsEnseignantPrincipal = $this->objEnseignantPrincipalServices->getEnseignantPrincipalsWithFilters();
            foreach ($objsEnseignantPrincipal as $objEnseignantPrincipal) {
                $arrToExport[] = $objEnseignantPrincipal->toCsv();
            }
        } else {
            // On récupère les données de tous les objets sélectionnés
            $arrIds = explode(',', $ids);
            foreach ($arrIds as $id) {
                if (empty($id)) {
                    continue;
                }
                $objEnseignantPrincipal = $this->objEnseignantPrincipalServices->getEnseignantPrincipalById($id);
                $arrToExport[] = $objEnseignantPrincipal->toCsv();
            }
        }
        
        // On retourne le message de réussite.
        return $this->exportFile($arrToExport, ucfirst(self::CST_ENSEIGNANTS_PRINCIPAUX));
    }
}
