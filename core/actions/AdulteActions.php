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
        $filters = $_POST['filter'];
        if ($ids==self::CST_ALL) {
            // On récupère toutes les données
            $objsAdulte = $objAdulteServices->getAdultesWithFilters();
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
            $objsAdulte = $objAdulteServices->getAdultesWithFilters($arrFilters);
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
    
    /**
     * @since 1.22.12.10
     * @version 1.22.12.10
     */
    public static function importFile()
    {
        $importType = $_POST['importType'];
        $dirName    = dirname(__FILE__).'/../../web/rsc/csv-files/';
        $fileName   = $dirName.'import_'.self::ONGLET_PARENTS.'.csv';
        if ($importType==self::ONGLET_PARENTS &&
            is_uploaded_file($_FILES['fileToImport']['tmp_name']) &&
            rename($_FILES['fileToImport']['tmp_name'], $fileName)) {
                $obj = new AdulteActions();
                return $obj->dealWithImport($fileName);
            }
    }
    
    /**
     * Vérifie la validité du fichier importé.
     * @param array $arrContent
     * @param string $notif
     * @param string $msg
     * @param string $msgError
     * @return boolean
     * @since v2.22.12.18
     * @version v2.22.12.18
     */
    public function controlerDonneesImport($arrContent, &$notif, &$msg, &$msgError)
    {
        $headerRow   = array_shift($arrContent);
        $objAdulte = new AdulteClass();
        $blnOk       = $objAdulte->controlerEntete($headerRow, $notif, $msgError);
        
        if ($blnOk) {
            $rkRow = 2;
            while (!empty($arrContent) && $blnOk) {
                $rowContent = array_shift($arrContent);
                $blnOk  = $objAdulte->controlerImportRow($rowContent, $notif, $msg);
                if (!$blnOk) {
                    $msgError = 'Ligne '.$rkRow.' : '.$msg;
                }
                $rkRow++;
            }
        }
        return $blnOk;
    }
}
