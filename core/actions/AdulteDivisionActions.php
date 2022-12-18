<?php
namespace core\actions;

use core\domain\AdulteDivisionClass;
use core\services\AdulteDivisionServices;

if (!defined('ABSPATH')) {
    die('Forbidden');
}
/**
 * AdulteDivisionActions
 * @author Hugues
 * @since 1.22.12.18
 * @version 1.22.12.18
 */
class AdulteDivisionActions extends LocalActions
{
    //////////////////////////////////////////////////
    // CONSTRUCT
    //////////////////////////////////////////////////
    /**
     * @since 1.22.12.18
     * @version 1.22.12.18
     */
    public static function getCsvExport()
    {
        $arrToExport = array();
        $objAdulteDivisionServices = new AdulteDivisionServices();

        // On initialise un objet et on récupère l'entête
        $objAdulteDivision = new AdulteDivisionClass();
        // On récupère l'entête
        $arrToExport[] = $objAdulteDivision->getCsvEntete();

        // On vérifie si on veut tous les éléments ou seulement une sélection
        $ids = $_POST[self::CST_IDS];
        $filters = $_POST['filter'];
        if ($ids==self::CST_ALL) {
            // On récupère toutes les données
            $objsAdulteDivision = $objAdulteDivisionServices->getAdulteDivisionsWithFilters();
            foreach ($objsAdulteDivision as $objAdulteDivision) {
                $arrToExport[] = $objAdulteDivision->toCsv();
            }
        } elseif ($ids=='filter') {
            // TODO : Pouvoir gérer plusieurs filtres
            list(, $value) = explode('=', $filters);
            $arrFilters = array();
                
            ///////////////////////////////////////////
            // Filtre sur Adhérent
            if ($value=='oui') {
                $adh = 1;
            } elseif ($value=='non') {
                $adh = 0;
            } else {
                $adh = '%';
            }
            $arrFilters[self::FIELD_ADHERENT] = $adh;
            ///////////////////////////////////////////

            ///////////////////////////////////////////
            // Filtre sur Division
            // TODO
            ///////////////////////////////////////////
            
            // On récupère toutes les données spécifiques au filtre
            $objsAdulteDivision = $objAdulteDivisionServices->getAdulteDivisionsWithFilters($arrFilters);
            foreach ($objsAdulteDivision as $objAdulteDivision) {
                $arrToExport[] = $objAdulteDivision->toCsv();
            }
        } else {
            // On récupère les données de tous les objets sélectionnés
            $arrIds = explode(',', $ids);
            foreach ($arrIds as $id) {
                if (empty($id)) {
                    continue;
                }
                $objAdulteDivision = $objAdulteDivisionServices->getAdulteDivisionById($id);
                $arrToExport[] = $objAdulteDivision->toCsv();
            }
        }
        
        // On retourne le message de réussite.
        return LocalActions::exportFile($arrToExport, ucfirst(self::SUBONGLET_PARENTS_DELEGUES));
    }
    
    /**
     * @since 1.22.12.18
     * @version 1.22.12.18
     */
    public static function importFile()
    {
        $importType = $_POST['importType'];
        $dirName    = dirname(__FILE__).'/../../web/rsc/csv-files/';
        $fileName   = $dirName.'import_'.self::SUBONGLET_PARENTS_DELEGUES.'.csv';
        
        if ($importType==self::SUBONGLET_PARENTS_DELEGUES &&
            is_uploaded_file($_FILES['fileToImport']['tmp_name']) &&
            rename($_FILES['fileToImport']['tmp_name'], $fileName)) {
                $obj = new AdulteDivisionActions();
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
        $headerRow = array_shift($arrContent);
        $objAdulteDivision = new AdulteDivisionClass();
        $blnOk = $objAdulteDivision->controlerEntete($headerRow, $notif, $msgError);
        
        if ($blnOk) {
            $rkRow = 2;
            while (!empty($arrContent) && $blnOk) {
                $rowContent = array_shift($arrContent);
                $blnOk  = $objAdulteDivision->controlerImportRow($rowContent, $notif, $msg);
                if (!$blnOk) {
                    $msgError = 'Ligne '.$rkRow.' : '.$msg;
                }
                $rkRow++;
            }
        }
        return $blnOk;
    }
}
