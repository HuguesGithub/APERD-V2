<?php
namespace core\actions;

use core\domain\DivisionClass;
use core\services\DivisionServices;

if (!defined('ABSPATH')) {
    die('Forbidden');
}
/**
 * DivisionActions
 * @author Hugues
 * @since 1.22.12.11
 * @version 1.22.12.11
 */
class DivisionActions extends LocalActions
{
    //////////////////////////////////////////////////
    // CONSTRUCT
    //////////////////////////////////////////////////
    /**
     * @since 1.22.12.11
     * @version 1.22.12.11
     */
    public static function getCsvExport()
    {
        $arrToExport = array();
        $objDivisionServices = new DivisionServices();

        // On initialise un objet et on récupère l'entête
        $objDivision = new DivisionClass();
        // On récupère l'entête
        $arrToExport[] = $objDivision->getCsvEntete();
        
        // On vérifie si on veut tous les éléments ou seulement une sélection
        $ids = $_POST[self::CST_IDS];
        if ($ids==self::CST_ALL) {
            // On récupère toutes les données
            $objsDivision = $objDivisionServices->getDivisionsWithFilters();
            foreach ($objsDivision as $objDivision) {
                $arrToExport[] = $objDivision->toCsv();
            }
        } else {
            // On récupère les données de tous les objets sélectionnés
            $arrIds = explode(',', $ids);
            foreach ($arrIds as $id) {
                if (empty($id)) {
                    continue;
                }
                $objDivision = $objDivisionServices->getDivisionById($id);
                $arrToExport[] = $objDivision->toCsv();
            }
        }
        
        // On retourne le message de réussite.
        return LocalActions::exportFile($arrToExport, ucfirst(self::ONGLET_DIVISIONS));
    }
    
    /**
     * @since 1.22.12.11
     * @version 1.22.12.11
     */
    public static function importFile()
    {
        $importType = $_POST['importType'];
        $dirName    = dirname(__FILE__).'/../../web/rsc/csv-files/';
        $fileName   = $dirName.'import_'.self::ONGLET_DIVISIONS.'.csv';
        if ($importType==self::ONGLET_DIVISIONS &&
            is_uploaded_file($_FILES['fileToImport']['tmp_name']) &&
            rename($_FILES['fileToImport']['tmp_name'], $fileName)) {
                $obj = new DivisionActions();
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
        $objDivision = new DivisionClass();
        $blnOk       = $objDivision->controlerEntete($headerRow, $notif, $msgError);
        
        if ($blnOk) {
            $rkRow = 2;
            while (!empty($arrContent) && $blnOk) {
                $rowContent = array_shift($arrContent);
                $blnOk  = $objDivision->controlerImportRow($rowContent, $notif, $msg);
                if (!$blnOk) {
                    $msgError = 'Ligne '.$rkRow.' : '.$msg;
                }
                $rkRow++;
            }
        }
        return $blnOk;
    }
}
