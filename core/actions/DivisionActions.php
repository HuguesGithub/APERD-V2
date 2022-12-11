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
    public function dealWithImport($srcFile)
    {
        $notif = '';
        $msg = '';
        $msgError = '';
        
        $fileContent = file_get_contents($srcFile);
        $arrContent  = explode(self::CST_EOL, $fileContent);
        
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
        
        if ($blnOk) {
            $notif = self::NOTIF_SUCCESS;
            $msg   = self::MSG_SUCCESS_IMPORT;
            $theList = '';
            $jsonAlertBlock = json_encode($this->getDismissableButton($notif, $msg));
            return '{"the-list": '.json_encode($theList).',"alertBlock": '.$jsonAlertBlock.'}';
        } else {
            return '{"alertBlock": '.json_encode($this->getDismissableButton($notif, $msgError)).'}';
        }
    }
    
    public function getDismissableButton($notif, $msg)
    {
        $strContent  = '<div class="alert alert-'.$notif.' alert-dismissible fade show" role="alert">';
        $strContent .= $msg.'<button type="button" class="close" data-dismiss="alert" aria-label="Close">';
        $strContent .= '<span aria-hidden="true">×</span></button></div>';
        return $strContent;
    }
    
}
