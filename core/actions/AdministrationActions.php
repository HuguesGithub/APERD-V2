<?php
namespace core\actions;

use core\domain\AdministrationClass;
use core\services\AdministrationServices;

if (!defined('ABSPATH')) {
    die('Forbidden');
}
/**
 * AdministrationActions
 * @author Hugues
 * @since 1.22.12.07
 * @version 1.22.12.08
 */
class AdministrationActions extends LocalActions
{
    //////////////////////////////////////////////////
    // CONSTRUCT
    //////////////////////////////////////////////////
    /**
     * @since 1.22.12.07
     * @version 1.22.12.08
     */
    public static function getCsvExport()
    {
        $arrToExport = array();
        $objAdministrationServices = new AdministrationServices();

        // On initialise un objet et on récupère l'entête
        $objAdministration = new AdministrationClass();
        // On récupère l'entête
        $arrToExport[] = $objAdministration->getCsvEntete();
        
        // On vérifie si on veut tous les éléments ou seulement une sélection
        $ids = $_POST[self::CST_IDS];
        if ($ids==self::CST_ALL) {
            // On récupère toutes les données
            $objsAdministration = $objAdministrationServices->getAdministrationsWithFilters();
            foreach ($objsAdministration as $objAdministration) {
                $arrToExport[] = $objAdministration->toCsv();
            }
        } else {
            // On récupère les données de tous les objets sélectionnés
            $arrIds = explode(',', $ids);
            foreach ($arrIds as $id) {
                if (empty($id)) {
                    continue;
                }
                $objAdministration = $objAdministrationServices->getAdministrationById($id);
                $arrToExport[] = $objAdministration->toCsv();
            }
        }
        
        // On retourne le message de réussite.
        return LocalActions::exportFile($arrToExport, ucfirst(self::ONGLET_ADMINISTRATIFS));
    }
    
    /**
     * @since 1.22.12.09
     * @version 1.22.12.09
     */
    public static function importFile()
    {
        $importType = $_POST['importType'];
        $dirName    = dirname(__FILE__).'/../../web/rsc/csv-files/';
        $fileName   = $dirName.'import_'.self::ONGLET_ADMINISTRATIFS.'.csv';
        if ($importType==self::ONGLET_ADMINISTRATIFS &&
            is_uploaded_file($_FILES['fileToImport']['tmp_name']) &&
            rename($_FILES['fileToImport']['tmp_name'], $fileName)) {
            $obj = new AdministrationActions();
            return $obj->dealWithImport($fileName);
        }
    }
    
    public function dealWithImport($srcFile)
    {
        $notif = '';
        $msgError = '';
        
        $fileContent = file_get_contents($srcFile);
        $arrContent  = explode(self::CST_EOL, $fileContent);

        $headerRow   = array_shift($arrContent);
        $objAdministration = new AdministrationClass();
        $blnOk       = $objAdministration->controlerEntete($headerRow, $notif, $msgError);
        
        if ($blnOk) {
            $rkRow = 2;
            while (!empty($arrContent) && $blnOk) {
                $rowContent = array_shift($arrContent);
                $blnOk  = $objAdministration->controlerImportRow($rowContent, $notif, $msg);
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
            return '{"the-list": '.json_encode($theList).',"alertBlock": '.json_encode($this->getDismissableButton($notif, $msg)).'}';
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
